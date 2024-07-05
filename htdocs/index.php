<?php
// セッションのスタート
session_start();

// エラーメッセージの初期化
if (!isset($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";
}

// ログインボタンが押された時の処理
if (isset($_POST['login'])) {
    // 入力が空でないかをチェック
    if (empty($_POST['user_id']) || empty($_POST['suica_number'])) {
        $_SESSION['index_err_msg'] = "ID・Suica番号を入力してからログインボタンを押してください";
        header("Location: ".$_SERVER['HTTP_REFERER']);
        exit;
    } else {
        try {
            // データベースへの接続
            $dsn = 'mysql:dbname=soubu;host=127.0.0.1'; // データベース名とホスト名を指定
            $username = 'testuser'; // ユーザ名
            $password = 'pass'; // パスワード
            $dbh = new PDO($dsn, $username, $password); // PDO インスタンスの作成

            // ユーザーIDに対応するSuica番号の取得
            $sql = 'SELECT suica_number FROM users WHERE user_id = :user_id';
            $stmt = $dbh->prepare($sql); // SQL 文の準備
            $stmt->bindParam(':user_id', $_POST['user_id']); // ユーザーIDをバインド
            $stmt->execute(); // SQL 文の実行
            $suica_number_hash = $stmt->fetchColumn(); // 結果の取得（1つのカラムの値を取得）

        // ログイン認証処理
        
            // ログイン成功時の処理
            $_SESSION['user_id'] = $_POST['user_id'];
            $_SESSION['index_err_msg'] = "";
            header("Location: okyaku.php");
            exit;
        
        }else{
        
    // ログイン失敗時の処理
    $_SESSION['index_err_msg'] = "ユーザーIDまたはSuica番号に不備があります";
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
        }


        } catch (PDOException $e) {
            // データベースへの接続に失敗した場合の処理
            print('データベースへの接続に失敗しました:' . $e->getMessage());
            die();
        }
    }
}

// ユーザ登録はこちらボタンが押された時の処理
if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = ""; // エラーメッセージの初期化
    header("Location: index2.php"); // ユーザ登録画面への遷移
    exit;
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
        Suica番号:<br>
        <input type="password" name="suica_number" required><br><br>
        <button type="submit" name="login">ログイン</button>
        <p><font color="red"><?php echo $_SESSION['index_err_msg']; ?></font></p><br>
        <button type="submit" name="register">ユーザ登録はこちら</button>
    </form>
</body>
</html>