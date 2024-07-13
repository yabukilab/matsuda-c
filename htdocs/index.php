<?php
session_start();

// データベース接続情報
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
