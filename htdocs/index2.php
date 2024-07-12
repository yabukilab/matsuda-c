<?php
session_start();

// データベース接続情報
require 'db.php';
    // 新規登録のSQLクエリを準備
    $sql = "INSERT INTO users (user_name, suica_number) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("準備に失敗しました: " . $conn->error);
    }

    // パラメータをバインド
    $stmt->bind_param("ss", $name, $suicaNumber); // パラメータを正しい順序でバインドする

    // クエリの実行
    if ($stmt->execute()) {
        echo "新規登録が成功しました";
    } else {
        echo "新規登録に失敗しました: " . $stmt->error;
    }

    // ステートメントを閉じる
    $stmt->close();

// データベース接続を閉じる
$conn->close();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規登録画面</title>
    <link rel="stylesheet" type="text/css" href="st.css">
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="grncar.jpg" alt="緑の車両">
        </div>
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
