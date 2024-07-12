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
    <style>
        .seat-btn {
            margin: 5px;
            padding: 10px;
            background-color: lightgray;
            border: none;
            cursor: pointer;
        }

        .seat-btn.selected {
            background-color: green;
            color: white;
        }
    </style>
</head>
<body>
    <h2>座席予約フォーム</h2>

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
        <div id="seat-selection">
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

                // 車両番号を取得
                $car_number = isset($_GET['car_number']) ? $_GET['car_number'] : null;

                if ($car_number) {
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
                }

                $conn->close();
            ?>
        </div><br>

        <input type="hidden" id="selected_seat_id" name="selected_seat_id" required>
        <input type="hidden" name="car_number" value="<?php echo htmlspecialchars($car_number); ?>">

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
