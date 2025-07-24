<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

$ln = isset($_POST['Login_number']) ? (int)$_POST['Login_number'] : 0;
$pw = $_POST['password'] ?? '';

// 認証チェック
$stmt = $db->prepare('SELECT password FROM mst_students WHERE Login_number = :ln');
$stmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row && $row['password'] === $pw) {
    $_SESSION['Login_number'] = $ln;
    header('Location: booking.php');
    exit;
}

// 認証失敗
header('Location: login-error.php');
exit;
