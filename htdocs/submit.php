<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "travel_info";

// 接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// フォームからのデータ取得
$boarding_station = $_POST['boarding-station'];
$departure_station = $_POST['departure-station'];
$travel_date = $_POST['travel-date'];
$travel_time = $_POST['travel-time'];

// SQL文の作成
$sql = "INSERT INTO user_selection (boarding_station, departure_station, travel_date, travel_time)
VALUES ('$boarding_station', '$departure_station', '$travel_date', '$travel_time')";

if ($conn->query($sql) === TRUE) {
    echo "新しいレコードが正常に作成されました";
} else {
    echo "エラー: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
