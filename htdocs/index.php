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
    <form method="POST" action="index.php">
        ユーザID:<br>
        <input type="text" name="user" required><br><br>
        suica番号:<br>
        <input type="password" name="suica_number" required><br><br>
        <button type="submit" name="login">ログイン</button>
        <p><font color="red"><?php echo $_SESSION['index_err_msg']; ?></font></p><br>
        <button type="submit" name="register">ユーザ登録はこちら</button>
    </form>
</body>
</html>

<?php
// ログインボタンが押された時の処理
if (isset($_POST['login'])) {
    // 入力枠に空が無いことをチェック
    if (empty($_POST['user']) || empty($_POST['suica_number'])) {
        $_SESSION['index_err_msg'] = "ID・suica番号を入力してからログインボタンを押して下さい";
        header("Location: ".$_SERVER['HTTP_REFERER']);  
        exit;
    } else {
        try {
            // データベースへの接続
            $dsn = 'mysql:dbname=soubu;host=127.0.0.1';
            $dbh = new PDO($dsn, 'db_admin', 'admin');

            // 入力されたIDのsuica番号取得
            $sql = 'SELECT suica_number FROM users WHERE user_id = :user_id'; // 正しいカラム名に修正
            $sth = $dbh->prepare($sql); // SQL文を実行変数へ投入
            $sth->bindParam(':user_id', $_POST['user_id']); // ユーザIDを実行変数に挿入
            $sth->execute(); // SQLの実行
            $suica_number = $sth->fetch(PDO::FETCH_ASSOC); // 処理結果の取得

            // ログイン認証処理
            if ($suica_number && password_verify($_POST['suica_number'], $suica_number['suica_number'])) { // 正しいカラム名に修正
                // ログイン成功時の処理
                $_SESSION['user_id'] = $_POST['user_id']; // ログインIDを格納したセッション変数を定義
                $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
                header("Location: okyaku.php");
                exit;
            } else {
                // ログイン失敗時にエラーメッセージを表示する処理
                $_SESSION['index_err_msg'] = "ユーザIDまたはsuica番号に不備があります";
                header("Location: ".$_SERVER['HTTP_REFERER']);
                exit;
            }

        // データベースへの接続に失敗した場合
        } catch (PDOException $e) {
            print('データベースへの接続に失敗しました:' . $e->getMessage());
            die();
        }
    }
}

// ユーザ登録はこちらボタンが押された時の処理
if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = ""; // エラーメッセージの削除
    header("Location: index2.php"); // ユーザ登録画面への遷移
    exit;
}
?>