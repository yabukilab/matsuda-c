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
$seat = $_POST['seat'];

// SQL文の作成
$sql = "INSERT INTO reservations (seat) VALUES ('$seat')";

if ($conn->query($sql) === TRUE) {
    header("Location: confirm.html");
    exit();
} else {
    echo "エラー: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
