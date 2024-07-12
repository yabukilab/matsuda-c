<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

$servername = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "pm_train";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("接続に失敗しました: " . $conn->connect_error);
}

if (!isset($_GET['reservation_id'])) {
    die("予約IDが指定されていません。");
}

$reservation_id = $_GET['reservation_id'];

$sql = "SELECT r.reservation_id, r.reservation_time, u.user_name, s.car_number, s.seat_number, st.station_name, sch.departure_time 
        FROM reservations r
        JOIN users u ON r.user_id = u.user_id
        JOIN seat s ON r.seat_id = s.seat_id
        JOIN schedules sch ON r.schedule_id = sch.schedule_id
        JOIN stations st ON sch.station_id = st.station_id
        WHERE r.reservation_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("指定された予約IDのデータが見つかりません。");
}

$row = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約確定画面</title>
</head>
<body>
    <h2>予約が確定しました</h2>
    <p>予約ID: <?php echo htmlspecialchars($row['reservation_id']); ?></p>
    <p>ユーザー名: <?php echo htmlspecialchars($row['user_name']); ?></p>
    <p>車両番号: <?php echo htmlspecialchars($row['car_number']); ?></p>
    <p>座席番号: <?php echo htmlspecialchars($row['seat_number']); ?></p>
    <p>駅: <?php echo htmlspecialchars($row['station_name']); ?></p>
    <p>出発時間: <?php echo htmlspecialchars($row['departure_time']); ?></p>
    <p>予約日時: <?php echo htmlspecialchars($row['reservation_time']); ?></p>
</body>
</html>

<?php
$conn->close();
?>
