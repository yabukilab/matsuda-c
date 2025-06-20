<?php
session_start();
if (!isset($_SESSION["student_id"])) {
    header("Location: index.php");
    exit;
}
?>

<h2>ログイン成功！ようこそ <?= htmlspecialchars($_SESSION["student_id"]) ?> さん</h2>
<a href="index.php">ログアウト</a>
