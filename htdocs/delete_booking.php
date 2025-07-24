<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';
if (empty($_SESSION['Login_number']) || empty($_GET['id'])) {
    header('Location: booking.php');
    exit;
}

$id = (int)$_GET['id'];
$stmt = $db->prepare('DELETE FROM comments WHERE id = :id');
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

header('Location: booking.php');
exit;
