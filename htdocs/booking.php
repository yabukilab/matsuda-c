<?php
// File: booking.php

// デバッグ表示（不要ならコメントアウト）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始＆DB接続
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログインチェック
if (empty($_SESSION['Login_number'])) {
    header('Location: index.php');
    exit;
}

$ln = (int)$_SESSION['Login_number'];

// 予約一覧取得（講師名＆種別名をJOIN）
$stmt = $db->prepare(
    'SELECT
        c.id,
        c.Lesson_date,
        c.Lesson_time,
        t.teacher_name,
        lt.lesson_type_name
     FROM comments c
     JOIN mst_teachers t      ON c.teacher_id      = t.teacher_id
     JOIN mst_lesson_types lt ON c.lesson_type_id = lt.lesson_type_id
     WHERE c.Login_number = :ln
     ORDER BY c.Lesson_date, c.Lesson_time'
);
$stmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>予約一覧</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header">
    <div style="display:flex; align-items:center; position: relative;">
  <div class="menu-btn" id="menuBtn">
    <span></span>
    <span></span>
    <span></span>
  </div>
  <div class="dropdown-menu" id="dropdownMenu">
    <a href="booking.php">Home</a>
    <a href="new_booking.php">新規予約</a>
    <a href="logout.php">Logout</a>
  </div>
  <!-- 既存の <h2> やタイトルが続きます -->
</div>

      <h2>🎹 ピアノレッスン予約システム</h2>
    </div>
    <div class="card bookings-section">
      <h3>🗓️ 予約一覧</h3><br>
      <?php if (empty($rows)): ?>
        <p class="no-bookings">予約がありません。</p>
      <?php else: ?>
        <?php foreach ($rows as $r): ?>
          <div class="booking-item" onclick="location.href='booking_detail.php?id=<?= $r['id'] ?>';" style="cursor:pointer;">
            <div class="booking-details">
              <p>【<?= h($r['lesson_type_name']) ?>】</p>
              <p>
                <?= (new DateTime($r['Lesson_date']))->format('n月j日') ?>　
                <?= (new DateTime($r['Lesson_time']))->format('G時i分') ?>～　
                担当：<?= h($r['teacher_name']) ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
  <script>
// メニューの表示／非表示
document.getElementById('menuBtn').addEventListener('click', function(e) {
  const menu = document.getElementById('dropdownMenu');
  menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
  e.stopPropagation();
});
// メニュー外クリックで閉じる
document.addEventListener('click', function() {
  document.getElementById('dropdownMenu').style.display = 'none';
});
</script>

</body>
</html>