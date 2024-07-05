<?php
session_start();

// データベース接続情報
$servername = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "train_pm";

// MySQLiのインスタンスを作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続をチェック
if ($conn->connect_error) {
    die("接続に失敗しました: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $suicaNumber = $_POST['suica-number']; // フォームのsuica-numberから受け取る

    // 新規登録のSQLクエリを準備
    $sql = "INSERT INTO users (suica_number, user_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("準備に失敗しました: " . $conn->error);
    }

    // パラメータをバインド
    $stmt->bind_param("ss", $suicaNumber, $name); // パラメータを正しい順序でバインドする

    // クエリの実行
    if ($stmt->execute()) {
        echo "新規登録が成功しました";
    } else {
        echo "新規登録に失敗しました: " . $stmt->error;
    }

    // ステートメントを閉じる
    $stmt->close();

    // ログイン画面に戻るボタンが押された時の処理
    if (isset($_POST['login'])) {
        $_SESSION['register_msg'] = ""; // 登録成功のメッセージの削除
        $_SESSION['register_err_msg'] = ""; // エラーメッセージの削除
        header("Location: matsuda-c/index.php"); // ログイン画面へ遷移
        exit(); // スクリプトを終了してリダイレクト
    }
}

// データベース接続を閉じる
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
</head>
<body>
    <div class="container">
        <h1>新規登録</h1>
        <form id="register-form" method="POST" action="">
            <div class="form-control">
                <label for="name">名前:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-control">
                <label for="suica-number">Suica番号:</label>
                <input type="text" id="suica-number" name="suica-number" required>
            </div>
            <div class="form-control">
                <button type="submit">登録</button>
            </div>
        </form>
        <div class="form-control">
            <a href="index.php">ログイン画面に戻る</a>
        </div>
    </div>
</body>
</html>
