<?php
session_start();

// セッションにユーザー名が保存されていない場合は、ログインページにリダイレクトする
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

require 'db.php'; // データベース接続の設定を読み込む

// POSTメソッドでリクエストが送信され、かつ「予約する」ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve'])) {
    // POSTデータから各種情報を取得する
    $seat_id = $_POST['seat_id'];
    $car_number = $_POST['car_number'];
    $schedule_id = $_POST['schedule_id'];
    $departure_station = $_POST['departure_station'];
    $arrival_station = $_POST['arrival_station'];
    $user_name = $_SESSION['user_name'];

    try {
        // ユーザー名からユーザーIDを取得するクエリを実行
        $stmt_user = $pdo->prepare("SELECT user_id FROM users WHERE user_name = ?");
        $stmt_user->execute([$user_name]);
        $user_id = $stmt_user->fetchColumn();

        // 現在の日時を取得
        $reservation_time = date('Y-m-d H:i:s');

        // reservationsテーブルに予約情報を挿入するクエリを準備・実行
        $stmt_reserve = $pdo->prepare("INSERT INTO reservations (user_id, seat_id, car_number, schedule_id, reservation_time, ) 
                                       VALUES (?, ?, ?, ?, ?, )");
        $stmt_reserve->execute([$user_id, $seat_id, $car_number, $schedule_id, $reservation_time, ]);

        // 座席の予約状況を更新するクエリを実行
        $stmt_update_seat = $pdo->prepare("UPDATE seat SET is_reserved = 1 WHERE seat_id = ?");
        $stmt_update_seat->execute([$seat_id]);

        // 成功メッセージを表示
        echo "予約が成功しました";

    } catch(PDOException $e) {
        // エラーが発生した場合はエラーメッセージを表示
        echo "予約の処理中にエラーが発生しました。エラー: " . $e->getMessage();
    }
}

// 予約可能な座席とスケジュールを表示するクエリを準備・実行
$sql_seats = "SELECT * FROM seat WHERE is_reserved = 0";
$result_seats = $pdo->query($sql_seats);

$sql_stations = "SELECT * FROM stations";
$result_stations = $pdo->query($sql_stations);

$sql_schedules = "SELECT DISTINCT schedule_id, departure_time FROM schedules";
$result_schedules = $pdo->query($sql_schedules);
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
        <select name="schedule_id" required>
            <?php
            if ($result_schedules->rowCount() > 0) {
                foreach ($result_schedules as $row) {
                    echo "<option value='{$row['schedule_id']}'>{$row['departure_time']}</option>";
                }
            } else {
                echo "<option value=''>スケジュールなし</option>";
            }
            ?>
        </select>

        <h3>乗車区間選択</h3>
        <label for="departure_station">乗車駅:</label>
        <select name="departure_station" id="departure_station" required>
            <?php
            if ($result_stations->rowCount() > 0) {
                foreach ($result_stations as $row) {
                    echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
                }
            } else {
                echo "<option value=''>駅情報なし</option>";
            }
            ?>
        </select>
        <label for="arrival_station">→</label>
        <select name="arrival_station" id="arrival_station" required>
            <?php
            $sql_arrival_stations = "SELECT station_id, station_name FROM stations";
            $result_arrival_stations = $pdo->query($sql_arrival_stations);

            if ($result_arrival_stations->rowCount() > 0) {
                foreach ($result_arrival_stations as $row) {
                    echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
                }
            } else {
                echo "<option value=''>駅情報なし</option>";
            }
            ?>
        </select>

        <h3>座席選択</h3>
        <select name="seat_id" required>
            <?php
            if ($result_seats->rowCount() > 0) {
                foreach ($result_seats as $row) {
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

    <form method="POST" action="kakunin.php">
        <button type="submit" name="check_reservation" class="btn">予約確認</button>
    </form>

    <form method="POST" action="sakujyo.php">
        <button type="submit" name="delete_reservation" class="btn">予約削除</button>
    </form>
    <div class="logo">
        <img src="画像2.png" alt="zaseki">
    </div>
</body>
</html>
