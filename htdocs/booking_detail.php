<?php
// File: booking_detail.php

// デバッグ表示（不要ならコメントアウト）
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// セッション開始＆DB接続
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログイン＆IDチェック
if (empty($_SESSION['Login_number']) || !isset($_GET['id'])) {
    header('Location: booking.php');
    exit;
}

$ln = (int)$_SESSION['Login_number'];
$id = (int)$_GET['id'];

// 詳細取得
$stmt = $db->prepare(
    'SELECT
        c.id,
        c.Lesson_date,
        c.Lesson_time,
        t.teacher_name,
        lt.lesson_type_name,
        c.Login_number
     FROM comments c
     JOIN mst_teachers t      ON c.teacher_id      = t.teacher_id
     JOIN mst_lesson_types lt ON c.lesson_type_id = lt.lesson_type_id
     WHERE c.id = :id AND c.Login_number = :ln'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':ln', $ln, PDO::PARAM_INT);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);

// 存在チェック
if (!$res) {
    header('Location: booking.php');
    exit;
}

// 表示用整形
$displayDate = (new DateTime($res['Lesson_date']))->format('n月j日');
$displayTime = (new DateTime($res['Lesson_time']))->format('G時i分');
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>予約詳細</title>
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
    <div class="card">
        <h3>🗓️ 予約詳細<br><br></h3>
      <div class="lesson-info">
        <p><strong>【種別】</strong> <?= h($res['lesson_type_name']) ?></p>
        <p><strong>日時：</strong> <?= $displayDate ?> <?= $displayTime ?></p>
        <p><strong>講師：</strong> <?= h($res['teacher_name']) ?></p>
        <p><strong>受講者番号：</strong> <?= h($res['Login_number']) ?></p>
      </div>
      <div class="actions" style="text-align:center; margin-top:20px;">
        <a href="booking.php" class="btn btn-small">◀︎ Back</a>
        <a href="edit_booking.php?id=<?= $res['id'] ?>" class="btn btn-small">編集</a>
        <a href="delete_booking.php?id=<?= $res['id'] ?>" class="btn btn-small" onclick="return confirm('この予約を削除してもよろしいですか？');">削除</a>
      </div>
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