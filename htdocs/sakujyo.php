<?php
require 'db.php'; // データベース接続の設定を読み込む

// 削除処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM reservations WHERE reservation_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// 予約データの取得
$sql = "SELECT reservation_id, seat_id, car_number, departure_station, arrival_station
        FROM reservations";
$result = $conn->query($sql);

// 取得した予約データを配列として取り出す
$reservations = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reservations[] = $row;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservation List</title>
</head>
<body>
    <h1>予約キャンセル</h1>
    <?php if (count($reservations) > 0): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>予約ID</th>
                    <th>座席</th>
                    <th>車両番号</th>
                    <th>出発駅</th>
                    <th>到着駅</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reservation['reservation_id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['seat_id']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['car_number']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['departure_station']); ?></td>
                        <td><?php echo htmlspecialchars($reservation['arrival_station']); ?></td>
                        <td>
                            <form method="POST" action="sakujyo.php" style="display: inline;">
                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($reservation['reservation_id']); ?>">
                                <input type="submit" value="キャンセル">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>予約がありません。</p>
    <?php endif; ?>
    <br>
    <a href="index.php">戻る</a>
    
    <?php
    // リソース解放
    if ($result) {
        $result->free();
    }
    // データベース接続を閉じる
    $conn->close();
    ?>
</body>
</html>
