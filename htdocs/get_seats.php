<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "pm_train";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$car_number = isset($_GET['car_number']) ? $_GET['car_number'] : '';
if ($car_number) {
    $sql = "SELECT seat_number FROM seat WHERE car_number = ? AND is_reserved = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $car_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $seats = [];
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
    echo json_encode($seats);
}

$conn->close();
?>
