<?php
session_start();

// ユーザーがログインしているか確認
if (!isset($_SESSION['user_name'])) {
    $_SESSION['index_err_msg'] = "まずログインしてください";
    header("Location: index.php");
    exit;
}

// データベース接続情報
require 'db.php';

$message = "";

// 予約キャンセルの処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    try {
        $sql = 'DELETE FROM reservations WHERE reservation_id = :reservation_id AND user_name = :user_name';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':reservation_id', $_POST['reservation_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "予約がキャンセルされました。";
        } else {
            $message = "予約のキャンセルに失敗しました。";
        }
    } catch (PDOException $e) {
        error_log('予約キャンセル時にエラーが発生しました: ' . $e->getMessage());
        $message = "予約のキャンセルに失敗しました。";
    }
}

// 予約情報を取得
try {
    $sql = 'SELECT reservation_id, seat_id, car_number, schedule_id, reservation_time FROM reservations WHERE user_name = :user_name';
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':user_name', $_SESSION['user_name'], PDO::PARAM_STR);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('予約情報取得時にエラーが発生しました: ' . $e->getMessage());
    $reservations = [];
}
// データベース接続を閉じる
$db = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>予約された座席のキャンセル</title>
   
</head>
<body>
    <h2>予約された座席のキャンセル</h2>
    <?php if (!empty($message)): ?>
        <p><?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>
    <?php if (empty($reservations)): ?>
        <p>予約された座席はありません。</p>
    <?php else: ?>
        <form method="POST" action="">
            <table>
                <tr>
                    <th>選択</th>
                    <th>予約ID</th>
                    <th>座席ID</th>
                    <th>車両番号</th>
                    <th>スケジュールID</th>
                    <th>予約時間</th>
                </tr>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><input type="radio" name="reservation_id" value="<?php echo htmlspecialchars($reservation['reservation_id'], ENT_QUOTES, 'UTF-8'); ?>" required></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($reservation['seat_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($reservation['car_number'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($reservation['schedule_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?php echo htmlspecialchars($reservation['reservation_time'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <br>
            <button type="submit">予約をキャンセルする</button>
        </form>
    <?php endif; ?>
    <br>
    <a href="index.php">戻る</a>
</body>
</html>