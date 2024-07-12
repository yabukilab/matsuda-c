<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席予約</title>
</head>
<body>
    <h2>座席予約フォーム</h2>

    <!-- 乗る駅と降りる駅の選択フォーム -->
    <form method="GET" action="">
        <label for="boarding_station">乗る駅:</label>
        <select id="boarding_station" name="boarding_station" required onchange="this.form.submit()">
            <option value="">駅名を選択してください</option>
            <?php
                // データベース接続情報
                $servername = "127.0.0.1";
                $username = "testuser";
                $password = "pass";
                $dbname = "pm_train";

                // データベース接続
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("データベースに接続できませんでした: " . $conn->connect_error);
                }

                // 駅名の取得
                $sql = "SELECT station_id, station_name FROM stations";
                $result = $conn->query($sql);

                // 駅名オプションを表示
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['boarding_station']) && $_GET['boarding_station'] == $row['station_id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['station_id']) . "' $selected>" . htmlspecialchars($row['station_name']) . "</option>";
                }
            ?>
        </select><br><br>

        <label for="destination_station">降りる駅:</label>
        <select id="destination_station" name="destination_station" required onchange="this.form.submit()">
            <option value="">駅名を選択してください</option>
            <?php
                // 同じように降りる駅のオプションを表示
                $result->data_seek(0);  // 結果セットを再度読み込み
                while ($row = $result->fetch_assoc()) {
                    $selected = (isset($_GET['destination_station']) && $_GET['destination_station'] == $row['station_id']) ? 'selected' : '';
                    echo "<option value='" . htmlspecialchars($row['station_id']) . "' $selected>" . htmlspecialchars($row['station_name']) . "</option>";
                }
                $conn->close();
            ?>
        </select><br><br>
    </form>

    <!-- 車両番号の選択フォーム -->
    <form method="GET" action="">
        <label for="car_number">車両番号:</label>
        <select id="car_number" name="car_number" required onchange="this.form.submit()">
            <option value="">車両を選択してください</option>
            <option value="4" <?php if (isset($_GET['car_number']) && $_GET['car_number'] == '4') echo 'selected'; ?>>車両4</option>
            <option value="5" <?php if (isset($_GET['car_number']) && $_GET['car_number'] == '5') echo 'selected'; ?>>車両5</option>
        </select><br>
    </form>

    <!-- 座席の選択フォーム -->
    <form method="POST" action="yoyaku.php">
        <label>座席:</label><br>
            <?php
                if (isset($_GET['car_number']) && isset($_GET['boarding_station']) && isset($_GET['destination_station'])) {
                    $car_number = $_GET['car_number'];

                    // データベース接続
                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("データベースに接続できませんでした: " . $conn->connect_error);
                    }

                    // 座席の取得
                    $sql = "SELECT seat_id, seat_number FROM seat WHERE car_number = ? AND is_reserved = 0";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $car_number);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // 座席ボタンを表示
                    while ($row = $result->fetch_assoc()) {
                        echo "<button type='button' class='seat-btn' data-seat-id='{$row['seat_id']}'>{$row['seat_number']}</button>";
                    }

                    $stmt->close();
                    $conn->close();
                }
            ?>
        </div><br>

        <input type="hidden" id="selected_seat_id" name="selected_seat_id" required>
        <input type="hidden" name="car_number" value="<?php echo isset($car_number) ? htmlspecialchars($car_number) : ''; ?>">
        <input type="hidden" name="boarding_station" value="<?php echo isset($_GET['boarding_station']) ? htmlspecialchars($_GET['boarding_station']) : ''; ?>">
        <input type="hidden" name="destination_station" value="<?php echo isset($_GET['destination_station']) ? htmlspecialchars($_GET['destination_station']) : ''; ?>">

        <input type="submit" name="submit" value="予約">
    </form>

    <script>
        document.querySelectorAll('.seat-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.seat-btn').forEach(btn => btn.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('selected_seat_id').value = this.getAttribute('data-seat-id');
            });
        });
    </script>
</body>
</html>
