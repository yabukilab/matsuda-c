<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

// 予約処理のためのコードを追加
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve'])) {
    $seat_id = $_POST['seat_id'];
    $car_number = $_POST['car_number'];
    $departure_time = $_POST['departure_time'];
    $departure_station = $_POST['departure_station'];
    $arrival_station = $_POST['arrival_station'];
    $user_name = $_SESSION['user_name'];

    // 同じ駅の選択を防ぐ
    if ($departure_station == $arrival_station) {
        echo "乗車駅と降車駅は同じにできません";
    } else {
        // departure_timeからschedule_idを取得
        $sql_schedule = "SELECT schedule_id FROM schedules WHERE departure_time = ?";
        $stmt_schedule = $db->prepare($sql_schedule);
        $stmt_schedule->bind_param("s", $departure_time);
        $stmt_schedule->execute();
        $result_schedule = $stmt_schedule->get_result();

        if ($result_schedule->num_rows > 0) {
            $schedule_id = $result_schedule->fetch_assoc()['schedule_id'];

            $sql_user = "SELECT user_id FROM users WHERE user_name = ?";
            $stmt_user = $db->prepare($sql_user);
            $stmt_user->bind_param("s", $user_name);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user_id = $result_user->fetch_assoc()['user_id'];

            // 既存の予約をチェック
            $sql_check = "SELECT * FROM reservations WHERE user_id = ? AND seat_id = ? AND schedule_id = ?";
            $stmt_check = $db->prepare($sql_check);
            $stmt_check->bind_param("iii", $user_id, $seat_id, $schedule_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows == 0) {
                $reservation_time = date('Y-m-d H:i:s');

                $sql_reserve = "INSERT INTO reservations (user_id, seat_id, car_number, schedule_id, reservation_time) VALUES (?, ?, ?, ?, ?)";
                $stmt_reserve = $db->prepare($sql_reserve);
                $stmt_reserve->bind_param("iiiss", $user_id, $seat_id, $car_number, $schedule_id, $reservation_time);

                if ($stmt_reserve->execute()) {
                    $sql_update_seat = "UPDATE seat SET is_reserved = 1 WHERE seat_id = ?";
                    $stmt_update_seat = $db->prepare($sql_update_seat);
                    $stmt_update_seat->bind_param("i", $seat_id);
                    $stmt_update_seat->execute();

                    echo "予約が成功しました";
                } else {
                    echo "予約に失敗しました: " . $stmt_reserve->error;
                }
            } else {
                echo "既に同じ区間の予約があります";
            }
        } else {
            echo "指定された時間に対応するスケジュールが見つかりません";
        }
    }
}

// 予約可能な座席とスケジュールを表示
$sql_seats = "SELECT * FROM seat WHERE is_reserved = 0";
$result_seats = $db->query($sql_seats);

$sql_schedules = "SELECT DISTINCT departure_time FROM schedules";
$result_schedules = $db->query($sql_schedules);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約ページ</title>
    <link rel="stylesheet" type="text/css" href="okyaku.css">
    <script>
        // JavaScriptで同じ駅を選択できないようにする
        function validateForm() {
            var departureStation = document.getElementById("departure_station").value;
            var arrivalStation = document.getElementById("arrival_station").value;
            if (departureStation === arrivalStation) {
                alert("乗車駅と降車駅は同じにできません");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h2>予約ページ</h2>
    <form method="POST" action="okyaku.php" onsubmit="return validateForm()">
        <h3>利用時間選択</h3>
        <label for="departure_time">時間:</label>
        <select name="departure_time" required>
            <div id="seat-selection">
                <div class="logo">
                <link rel="stylesheet" type="text/css" href="okyaku.css">
                    
                </div>

                <?php
                if ($result_schedules->num_rows > 0) {
                    while ($row = $result_schedules->fetch_assoc()) {
                        echo "<option value='{$row['departure_time']}'>{$row['departure_time']}</option>";
                    }
                } else {
                    echo "<option value=''>スケジュールなし</option>";
                }
                ?>
            </select>

            <h3>乗車区間選択</h3>
            <label for="departure_station">乗車区間:</label>
            <select name="departure_station" id="departure_station" required>
                <?php
                $sql_stations = "SELECT station_id, station_name FROM stations";
                $result_stations = $db->query($sql_stations);
                while ($row = $result_stations->fetch_assoc()) {
                    echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
                }
                ?>
            </select>
            <label for="arrival_station">→</label>
            <select name="arrival_station" id="arrival_station" required>
                <?php
                $result_stations = $db->query($sql_stations);
                while ($row = $result_stations->fetch_assoc()) {
                    echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
                }
                ?>
            </select>

            <h3>座席選択</h3>
            <select name="seat_id" required>
                <?php
                if ($result_seats->num_rows > 0) {
                    while ($row = $result_seats->fetch_assoc()) {
                        echo "<option value='" . $row['seat_id'] . "' data-car-number='" . $row['car_number'] . "'>車両: " . $row['car_number'] . " 座席: " . $row['seat_number'] . "</option>";
                    }
                } else {
                    echo "<option value=''>空席なし</option>";
                }
                ?>
            </select>

            <input type="hidden" name="car_number" id="car_number" value="">

            <br><br>
            <button type="submit" name="reserve">予約する</button>
        </form>

        <br>
        <button class="back-button" onclick="window.location.href='index.php'">戻る</button>


        <script>
            // JavaScriptで座席選択に基づいて車両番号を設定
            document.querySelector("select[name='seat_id']").addEventListener("change", function() {
                var selectedOption = this.options[this.selectedIndex];
                var carNumber = selectedOption.getAttribute("data-car-number");
                document.getElementById("car_number").value = carNumber;
            });
        </script>
         <div class="logo">
        <img src="画像2.png" alt="zaseki">
    </div>
    </body>
    </html>

    <?php
    $conn->close();
    ?>
