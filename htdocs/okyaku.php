<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "pm_train";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>総武快速の座席予約</title>
</head>
<body>

<h2>総武快速の座席予約</h2>

<form action="zaseki.php" method="POST">

    <div class="form-group">
        <label for="departure_station">乗車区間:</label>
        <select name="departure_station" id="departure_station" required>
            <?php
            $sql = "SELECT station_id, station_name FROM stations";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
            }
            ?>
        </select>
        <label for="arrival_station">→</label>
        <select name="arrival_station" id="arrival_station" required>
            <?php
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['station_id']}'>{$row['station_name']}</option>";
            }
            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="departure_time">時間:</label>
        <select name="departure_time" id="departure_time" required>
            <?php
            $sql = "SELECT DISTINCT departure_time FROM schedules";
            $result = $conn->query($sql);
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['departure_time']}'>{$row['departure_time']}</option>";
            }
            ?>
        </select>
    </div>
    <button type="submit">次へ</button>
</form>


</body>
</html>

<?php
$conn->close();
?>
