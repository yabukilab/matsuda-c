<?php
session_start();

// データベース接続情報
require 'db.php';

// ログインしているかどうかを確認する関数
function isLoggedIn() {
    return isset($_SESSION['user_name']);
}

// リダイレクト関数
function redirect($url) {
    header("Location: $url");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['user'];
        $suicaNumber = $_POST['suica_number'];

        // ログインの処理
        // SQLクエリを準備
        $sql = "SELECT * FROM users WHERE user_name = :user AND suica_number = :suicaNumber";
        $stmt = $db->prepare($sql);

        if ($stmt === false) {
            die("準備に失敗しました: " . implode(", ", $db->errorInfo()));
        }

        // パラメータをバインド
        $stmt->bindParam(':user', $user, PDO::PARAM_STR);
        $stmt->bindParam(':suicaNumber', $suicaNumber, PDO::PARAM_STR);

        // クエリの実行
        try {
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['user_name'] = $user['user_name'];
                // ユーザーがログインしたらokyaku.phpにリダイレクト
                redirect("okyaku.php");
            } else {
                $_SESSION['index_err_msg'] = "ユーザ名またはSuica番号が正しくありません";
            }
        } catch (PDOException $e) {
            die("クエリ実行に失敗しました: " . $e->getMessage());
        }

        // ステートメントを閉じる
        $stmt = null;
    } elseif (isset($_POST['register'])) {
        redirect("index2.php");
    } elseif (isset($_POST['delete_reservation'])) {
        if (isLoggedIn()) {
            redirect("sakujyo.php");
        } else {
            $_SESSION['index_err_msg'] = "まずはログインしてください";
        }
    } elseif (isset($_POST['check_reservation'])) {
        if (isLoggedIn()) {
            redirect("kakunin.php");
        } else {
            $_SESSION['index_err_msg'] = "まずはログインしてください";
        }
    }
}

// データベース接続を閉じる
$db = null;
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
        <form method="POST" action="">
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
            <?php
            if (isset($_SESSION['index_err_msg'])) {
                echo '<p><font color="red">' . $_SESSION['index_err_msg'] . '</font></p><br>';
                unset($_SESSION['index_err_msg']);
            }
            ?>
        </form>
        <!-- ボタンのフォームを別にする -->
        <form method="POST" action="">
            <div class="form-group">
                <button type="submit" name="register" class="btn">ユーザ登録はこちら</button>
            </div>
        </form>
        <form method="POST" action="">
            <div class="form-group">
                <button type="submit" name="delete_reservation" class="btn">予約削除</button>
            </div>
        </form>
        <form method="POST" action="">
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
