<?php
session_start();
$servername = "127.0.0.1";
$username = "testuser";
$password = "pass";
$dbname = "train_pm";

// データベース接続の作成
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続チェック
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// POSTリクエストで必要なデータが送信されたことを確認
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $departure_station = $_POST['departure_station'];
    $arrival_station = $_POST['arrival_station'];
    $departure_time = $_POST['departure_time'];
    $selected_seat_id = $_POST['selected_seat_id'];
    $selected_car_number = $_POST['car_number']; // 車両番号もPOSTデータから取得
    $customer_name = $_SESSION['user_id']; // Assume user_id is stored in session

    // 座席IDと車両番号が有効かどうかをチェック
    $sql_seat_check = "SELECT seat_id FROM seat WHERE seat_id = ? AND car_number = ?";
    $stmt_seat_check = $conn->prepare($sql_seat_check);
    if (!$stmt_seat_check) {
        die("Preparation failed for seat check: " . $conn->error);
    }
    $stmt_seat_check->bind_param("ss", $selected_seat_id, $selected_car_number);
    $stmt_seat_check->execute();
    $result_seat_check = $stmt_seat_check->get_result();
    if ($result_seat_check->num_rows == 0) {
        die("Invalid seat ID or car number provided.");
    }

    // スケジュールIDを取得するためのSQLクエリ
    $sql_schedule = "SELECT schedule_id FROM schedules WHERE departure_station = ? AND arrival_station = ? AND departure_time = ?";
    $stmt_schedule = $conn->prepare($sql_schedule);

    // ステートメントの準備に失敗した場合のエラーハンドリング
    if (!$stmt_schedule) {
        die("Preparation failed for schedule: " . $conn->error);
    }

    $stmt_schedule->bind_param("sss", $departure_station, $arrival_station, $departure_time);
    $stmt_schedule->execute();
    $result_schedule = $stmt_schedule->get_result();

    // スケジュールが見つからなかった場合のエラーハンドリング
    if ($result_schedule->num_rows == 0) {
        die("No schedule found for the provided criteria.");
    }

    $schedule = $result_schedule->fetch_assoc();
    $schedule_id = $schedule['schedule_id'];

    // 予約をデータベースに保存
    $sql = "INSERT INTO reservations (seat_id, car_number, schedule_id, customer_name, reservation_time) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    // デバッグ情報の追加
    if (!$stmt) {
        die("Preparation failed: " . $conn->error);
    }

    $stmt->bind_param("ssss", $selected_seat_id, $selected_car_number, $schedule_id, $customer_name);

    if ($stmt->execute()) {
        // 座席の状態を予約済みに更新
        $sql_update = "UPDATE seat SET is_reserved = 1 WHERE seat_id = ? AND car_number = ?";
        $stmt_update = $conn->prepare($sql_update);

        // デバッグ情報の追加
        if (!$stmt_update) {
            die("Update preparation failed: " . $conn->error);
        }

        $stmt_update->bind_param("ss", $selected_seat_id, $selected_car_number);
        if ($stmt_update->execute()) {
            echo "予約が完了しました。";
        } else {
            echo "座席の更新に失敗しました: " . $stmt_update->error;
        }
    } else {
        echo "予約に失敗しました: " . $stmt->error;
    }
} else {
    echo "無効なリクエストです。";
}

$conn->close();
?>
