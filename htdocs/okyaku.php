<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

require 'db.php';

// 予約可能な座席とスケジュールを表示
$sql_seats = "SELECT * FROM seat WHERE is_reserved = 0";
$result_seats = $db->query($sql_seats);

$sql_schedules = "SELECT DISTINCT departure_time FROM schedules";
$result_schedules = $db->query($sql_schedules);

// 駅情報を取得
$sql_stations = "SELECT station_id, station_name FROM stations";
$result_stations = $db->query($sql_stations);
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
            if ($result_stations->num_rows > 0) {
                while ($row = $result_stations->fetch_assoc()) {
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
            if ($result_stations->num_rows > 0) {
                while ($row = $result_stations->fetch_assoc()) {
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

