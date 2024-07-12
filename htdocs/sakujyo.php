<?php
session_start();

// ユーザーがログインしているか確認
if (!isset($_SESSION['user_id'])) {
    $_SESSION['index_err_msg'] = "まずログインしてください";
    header("Location: index.php");
    exit;
}

try {
    $dsn = 'mysql:dbname=pm_train;host=127.0.0.1';
    $username = 'testuser';
    $password = 'pass';
    $dbh = new PDO($dsn, $username, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーモードを設定

    $message = "";
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
        $sql = 'DELETE FROM reservations WHERE reservation_id = :reservation_id AND user_id = :user_id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':reservation_id', $_POST['reservation_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $message = "予約がキャンセルされました。";
        } else {
            $message = "予約のキャンセルに失敗しました。";
        }
    }

    $sql = 'SELECT reservation_id, seat_id, car_number, schedule_id, reservation_time FROM reservations WHERE user_id = :user_id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('データベースへの接続に失敗しました:' . $e->getMessage());
    die('データベースへの接続に失敗しました: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>予約された座席のキャンセル</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
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
