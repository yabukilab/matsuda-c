<?php
require 'db.php'; // データベース接続の設定を読み込む

// 削除処理
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $sql = "DELETE FROM reservations WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// 予約データの取得
$sql = "SELECT id, name, date, time FROM reservations";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservation List</title>
</head>
<body>
    <h1>Reservation List</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["date"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["time"]) . "</td>";
                echo "<td>";
                echo "<form method='POST' action='sakujyo.php' style='display:inline;'>";
                echo "<input type='hidden' name='delete_id' value='" . htmlspecialchars($row["id"]) . "'>";
                echo "<input type='submit' value='Delete'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No reservations found</td></tr>";
        }
        ?>
    </table>
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
