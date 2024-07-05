<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "train";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
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
<form 

    <label for="car_number">車両番号:</label>
    <select id="car_number" name="car_number" required>
        <option value="">車両を選択してください</option>
        <option value="4">車両4</option>
        <option value="5">車両5</option>
    </select><br>

    <label>座席:</label><br>
    <div id="seat-selection">
        <?php
        // データベース接続情報
        // データベース接続情報
$servername = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "soubu";


        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("接続に失敗しました: " . $conn->connect_error);
        }

        $sql = "SELECT seat_number, car_number FROM seat WHERE car_number = 4 AND is_reserved = 0"; // ここでは車両4のみを表示しています
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<button type='button' class='seat-btn' data-seat-id='{$row['seat_number']}'>{$row['seat_number']}</button>";
        }

        $conn->close();
        ?>
    </div><br>

    <input type="hidden" id="selected_seat_id" name="selected_seat_id" required>

    <input type="submit" value="予約">
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
<?php
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'], $_POST['daparture_station'], $_POST['arrival_station'], $_POST['departure_time'], $_POST['car_number'], $_POST['seat_unmber'])) {
        $user = $_POST['user_id'];
        $daparture_station = $_POST['daparture_station'];
        $arrival_station = $_POST['arrival_station'];
        $departure_time = $_POST['departure_time'];
        $car = $_POST['car_number'];
        $seat = $_SESSION['seat_unmber'];

       
        // 座席を予約する
        $sql = "INSERT INTO reservations (user_id, daparture_station, arrival_station, departure_time, car_number, seat_number) VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $seat_id, $car_number, $schedule_id, $customer_name);

        if ($stmt->execute()) {
            // 座席の状態を予約済みに更新
            $sql_update = "UPDATE seat SET is_reserved = 1 WHERE seat_number = ? AND car_number = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("ss", $seat_id, $car_number);
            if ($stmt_update->execute()) {
                echo "予約が完了しました。";
            } else {
                echo "座席の更新に失敗しました: " . $stmt_update->error;
            }
        } else {
            echo "予約に失敗しました: " . $stmt->error;
        }
    } else {
        echo "利用号車または座が指定されていません。";
    }
} else {
    echo "無効なリクエストです。";
}
?>