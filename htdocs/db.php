<?php
// db.php
$host = 'localhost';
$dbname = 'piano';
$user = 'root';
$pass = ''; // XAMPPのデフォルトは空

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    echo "DB接続エラー: " . $e->getMessage();
    exit;
}
?>
