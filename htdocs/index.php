<?php
session_start();
require 'db.php';

$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["student_id"];
    $pass = $_POST["password"];

    $stmt = $db->prepare("SELECT * FROM mst_students WHERE student_id = ? AND password = ?");
    $stmt->execute([$id, $pass]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION["student_id"] = $id;
        header("Location: home.php");
        exit;
    } else {
        $error = "受講者番号またはパスワードが違います";
    }
}
?>

<form method="post">
    <h2>ログイン</h2>
    <input name="student_id" placeholder="受講者番号" required><br>
    <input name="password" type="password" placeholder="パスワード" required><br>
    <button type="submit">ログイン</button>
    <p style="color:red"><?= htmlspecialchars($error) ?></p>
</form>
