<?php
session_start();

// データベース接続設定
$host = 'localhost';
$dbname = 'piano';
$user = 'root';
$pass = ''; // XAMPPの初期設定

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("DB接続エラー: " . $e->getMessage());
}

// ログイン処理
$error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    $id = $_POST["student_id"];
    $pw = $_POST["password"];

    $stmt = $db->prepare("SELECT * FROM mst_students WHERE student_id = ? AND password = ?");
    $stmt->execute([$id, $pw]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION["student_id"] = $id;
    } else {
        $error = "受講者番号またはパスワードが違います";
    }
}

// ログアウト処理
if (isset($_GET["logout"])) {
    session_destroy();
    header("Location: piano.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ピアノ予約システム</title>
</head>
<body>
    <?php if (!isset($_SESSION["student_id"])): ?>
        <h2>ログイン</h2>
        <form method="post">
            <input name="student_id" placeholder="受講者番号" required><br>
            <input name="password" type="password" placeholder="パスワード" required><br>
            <button type="submit" name="login">ログイン</button>
        </form>
        <p style="color:red"><?= htmlspecialchars($error) ?></p>
    <?php else: ?>
        <h2>ようこそ <?= htmlspecialchars($_SESSION["student_id"]) ?> さん</h2>
        <p>（ここに予約・削除・確認機能を追加できます）</p>
        <a href="?logout=1">ログアウト</a>
    <?php endif; ?>
</body>
</html>
