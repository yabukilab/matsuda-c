<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (!isset($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
    <h2>ログイン</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        ユーザID:<br>
        <input type="text" name="user_id" required><br><br>
        suica番号:<br>
        <input type="password" name="suica_number" required><br><br>
        <button type="submit" name="login">ログイン</button>
        <button type="submit" name="register">ユーザ登録はこちら</button>
        <p><font color="red"><?php echo htmlspecialchars($_SESSION['index_err_msg']); ?></font></p><br>
    </form>
</body>
</html>

<?php
// ログインボタンが押された時の処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    try {
        // データベース接続設定ファイルを読み込む
        require 'db_config.php';

        // 入力値を取得し、エスケープする
        $user_id = htmlspecialchars($_POST['user_id'], ENT_QUOTES, 'UTF-8');
        $suica_number = htmlspecialchars($_POST['suica_number'], ENT_QUOTES, 'UTF-8');

        // SQL準備
        $sql = 'SELECT name FROM users WHERE user_id = ? AND suica_number = ?';
        if ($rec === false) {
            $_SESSION['index_err_msg'] = "idまたはsuica番号が違います。";
        } else {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['index_err_msg'] = "";
            header('Location: okyaku.php');
            exit();
        }
    } catch (PDOException $e) {
        echo 'データベースエラー: ' . $e->getMessage();
        exit();
    }
}

// ユーザ登録はこちらボタンが押された時の処理
if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = "";
    header("Location: index2.php");
    exit();
}
?>
