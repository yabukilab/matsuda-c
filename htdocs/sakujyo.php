<?php
// データベースの接続情報
$host = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "pm_train";

try {
    // データベースへの接続
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // エラーモードを例外モードに設定
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // reservationsテーブルからデータを取得
    $stmt = $pdo->prepare("SELECT reservation_id, user_id, seat_id, car_number, schedule_id, reservation_time FROM reservations");
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // フォームが送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 選択された予約IDを取得
        if (isset($_POST['reservation_id'])) {
            $reservation_id = $_POST['reservation_id'];
            
            // reservationsテーブルから該当の予約IDのデータを削除
            $stmt = $pdo->prepare("DELETE FROM reservations WHERE reservation_id = :reservation_id");
            $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // 削除が成功したらメッセージを表示
            echo "予約ID {$reservation_id} の予約をキャンセルしました。";
        } else {
            echo "予約IDが選択されていません。";
        }
    }
} catch(PDOException $e) {
    // エラーが発生した場合はエラーメッセージを表示
    echo "エラー: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約キャンセル</title>
</head>
<body>
    <h1>予約キャンセル</h1>
    <form method="post">
        <label for="reservation_id">キャンセルしたい予約を選択してください:</label>
        <select name="reservation_id" id="reservation_id">
            <?php foreach ($reservations as $reservation): ?>
                <option value="<?php echo $reservation['reservation_id']; ?>">
                    <?php echo "予約ID: {$reservation['reservation_id']} - ユーザID: {$reservation['user_id']} - 座席ID: {$reservation['seat_id']}"; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">予約キャンセル</button>
    </form>
    <br>
    <button class="back-button" onclick="window.location.href='index.php'">戻る</button>
    <img src="grncar.jpg" alt="Green Car Logo">
            </br>
</body>
</html>
