<?php
session_start();

if (!isset($_SESSION['index_err_msg'])) {
    $_SESSION['index_err_msg'] = "";
}

if (isset($_POST['login'])) {
    if (empty($_POST['user']) || empty($_POST['suica_number'])) {
        $_SESSION['index_err_msg'] = "ID・Suica番号を入力してからログインボタンを押してください";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        try {
            require 'db.php';

            $sql = 'SELECT user_id FROM users WHERE user_name = :user_name AND suica_number = :suica_number';
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':user_name', $_POST['user']);
            $stmt->bindParam(':suica_number', $_POST['suica_number']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                $_SESSION['user_name'] = $_POST['user'];
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['index_err_msg'] = "";
                header("Location: okyaku.php");
                exit;
            } else {
                $_SESSION['index_err_msg'] = "ユーザーIDまたはSuica番号に不備があります";
                header("Location: " . $_SERVER['HTTP_REFERER']);
                exit;
            }
        } catch (PDOException $e) {
            error_log('データベースへの接続に失敗しました:' . $e->getMessage());
            die('データベースへの接続に失敗しました: ' . $e->getMessage());
        }
    }
}

if (isset($_POST['register'])) {
    $_SESSION['index_err_msg'] = "";
    header("Location: index2.php");
    exit;
}

if (isset($_POST['delete_reservation'])) {
    $_SESSION['index_err_msg'] = "";
    header("Location: sakujyo.php");
    exit;
}

if (isset($_POST['check_reservation'])) {
    $_SESSION['index_err_msg'] = "";
    header("Location: kakunin.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>
<body>
    <div class="container">
        <h2>ログイン</h2>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label for="user">ユーザID</label>
                <input type="text" name="user" id="user" required>
            </div>
            <div class="form-group">
                <label for="suica_number">Suica番号</label>
                <input type="password" name="suica_number" id="suica_number" required>
            </div>
            <div class="form-group">
                <button type="submit" name="login" class="btn">ログイン</button>
            </div>
            <p><font color="red"><?php echo $_SESSION['index_err_msg']; ?></font></p><br>
            <div class="form-group">
                <button type="submit" name="register" class="btn">ユーザ登録はこちら</button>
            </div>
            <div class="form-group">
                <button type="submit" name="delete_reservation" class="btn">予約削除</button>
            </div>
            <div class="form-group">
                <button type="submit" name="check_reservation" class="btn">予約確認</button>
            </div>
        </form>
    </div>
    <div class="logo">
        <img src="11020306.png" alt="JR Logo">
        <img src="grncar.jpg" alt="Green Car Logo">
    </div>
</body>
</html>
