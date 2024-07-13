<?php
session_start();

// データベース接続情報
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $name = $_POST['name'];
    $suicaNumber = $_POST['suica-number'];

    // suica_hash のデフォルト値を空文字列として追加する
    $suicaHash = '';

    // 新規登録のSQLクエリを準備
    $sql = "INSERT INTO users (user_name, suica_number, suica_hash) VALUES (:name, :suicaNumber, :suicaHash)";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        die("準備に失敗しました: " . $db->errorInfo()[2]);
    }

    // パラメータをバインド
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':suicaNumber', $suicaNumber, PDO::PARAM_STR);
    $stmt->bindParam(':suicaHash', $suicaHash, PDO::PARAM_STR); // suica_hash をバインド

    // クエリの実行
    try {
        $stmt->execute();
        echo "新規登録が成功しました";
    } catch(PDOException $e) {
        echo "新規登録に失敗しました: " . $e->getMessage();
    }

    // ステートメントを閉じる
    $stmt = null;
}

// データベース接続を閉じる
$db = null;
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
                <button type="submit" name="register">登録</button>
            </div>
        </form>
        <div class="form-control">
            <a href="index.php">ログイン画面に戻る</a>
        </div>
    </div>
</body>
</html>
