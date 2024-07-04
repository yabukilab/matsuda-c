<?php
// データベース接続情報
$dsn = 'mysql:dbname=pm_train;host=127.0.0.1';
$db_user = 'db_admin';
$db_password = 'admin';

try {
    // データベースへの接続
    $dbh = new PDO($dsn, $db_user, $db_password);

    // フォームが送信されたかチェック
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // フォームから送信されたデータを取得
        $user = $_POST['user'];
        $suica_number = $_POST['suica_number'];

        // suica番号をハッシュ化
        $hashed_suica_number = password_hash($suica_number, PASSWORD_DEFAULT);

        // SQLクエリを準備して実行
        $sql = "INSERT INTO users (user, suica_number) VALUES (:user, :suica_number)";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':user', $user);
        $stmt->bindParam(':suica_number', $hashed_suica_number);

        if ($stmt->execute()) {
            echo "登録成功";
            // ログインページにリダイレクト
            header("Location: index.php");
            exit();
        } else {
            echo "登録失敗: " . $stmt->errorInfo()[2];
        }
    }
} catch (PDOException $e) {
    echo 'データベースへの接続に失敗しました: ' . $e->getMessage();
    die();
}
?>
