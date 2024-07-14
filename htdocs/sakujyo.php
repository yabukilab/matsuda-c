<?php
session_start();
if (!isset($_SESSION['user_name'])) {
    header("Location: index.php");
    exit;
}

require 'db.php'; // データベース接続の設定を読み込む

try {
    // ユーザー名からユーザーIDを取得
    $stmt_user = $db->prepare("SELECT user_id FROM users WHERE user_name = :user_name");
    $stmt_user->bindParam(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt_user->execute();
    $user_id = $stmt_user->fetchColumn();

    // ログインしているユーザーの予約データを取得
    $stmt = $db->prepare("SELECT reservation_id, user_id, seat_id, car_number, schedule_id, reservation_time FROM reservations WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // フォームが送信された場合
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 選択された予約IDを取得
        if (isset($_POST['reservation_id'])) {
            $reservation_id = $_POST['reservation_id'];
            
            // reservationsテーブルから該当の予約IDのデータを削除
            $stmt = $db->prepare("DELETE FROM reservations WHERE reservation_id = :reservation_id AND user_id = :user_id");
            $stmt->bindParam(':reservation_id', $reservation_id, PDO::PARAM_INT);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            // 削除が成功したらメッセージを表示
            echo "予約ID {$reservation_id} の予約をキャンセルしました。";
            
            // 予約データを再取得
            $stmt->execute();
            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            echo "予約IDが選択されていません。";
        }
    }
} catch(PDOException $e) {
    // エラーが発生した場合はエラーメッセージを表示
    echo "エラー: " . h($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約キャンセル</title>
    <link rel="stylesheet" type="text/css" href="sakujyo.css">
</head>
<body>
    <h1>予約キャンセル</h1>
    <form method="post">
        <label for="reservation_id">キャンセルしたい予約を選択してください:</label>
        <select name="reservation_id" id="reservation_id">
            <?php foreach ($reservations as $reservation): ?>
                <option value="<?php echo h($reservation['reservation_id']); ?>">
                    <?php echo "予約ID: " . h($reservation['reservation_id']) . " - 座席ID: " . h($reservation['seat_id']) . " - 車両番号: " . h($reservation['car_number']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">予約キャンセル</button>
    </form>
    <br>
    <button class="back-button" onclick="window.location.href='index.php'">戻る</button>
    <img src="grncar.jpg" alt="Green Car Logo">
</body>
</html>
