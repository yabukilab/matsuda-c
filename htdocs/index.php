<?php
// セッションのスタート及びセッション変数の定義
session_start();
if (!isset($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username"><br>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password"><br>

        <button type="submit">ログイン</button><br>

        <!-- CSSを使用した修正部分 -->
        <p class="error-message">エラーメッセージ</p>
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
        $sql = 'SELECT user_id FROM users WHERE user_id = ? AND suica_number = ?';
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
