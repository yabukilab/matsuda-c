<?php
// File: update_booking.php

// セッション開始＆DB接続
if (session_status()===PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// ログインチェック
if (empty($_SESSION['Login_number'])) { header('Location: index.php'); exit; }

// POST取得
$ln             = (int)$_SESSION['Login_number'];
$id             = (int)($_POST['id']             ?? 0);
$teacher_id     = (int)($_POST['teacher_id']     ?? 0);
$lesson_type_id = (int)($_POST['lesson_type_id'] ?? 0);
$date           = $_POST['Lesson_date']          ?? '';
$time           = $_POST['Lesson_time']          ?? '';

if (!$id || !$teacher_id || !$lesson_type_id || !$date || !$time) {
    header('Location: edit_booking.php?id=' . $id);
    exit;
}

// 更新
$stmt = $db->prepare(
    'UPDATE comments SET
         teacher_id = :tid,
         lesson_type_id = :ltid,
         Lesson_date = :d,
         Lesson_time = :t
     WHERE id = :id AND Login_number = :ln'
);
$stmt->bindValue(':tid',  $teacher_id,     PDO::PARAM_INT);
$stmt->bindValue(':ltid', $lesson_type_id, PDO::PARAM_INT);
$stmt->bindValue(':d',    $date,           PDO::PARAM_STR);
$stmt->bindValue(':t',    $time,           PDO::PARAM_STR);
$stmt->bindValue(':id',   $id,             PDO::PARAM_INT);
$stmt->bindValue(':ln',   $ln,             PDO::PARAM_INT);
$stmt->execute();

// 確認画面用取得
$stmt = $db->prepare(
    'SELECT t.teacher_name, lt.lesson_type_name, c.Lesson_date, c.Lesson_time
     FROM comments c
     JOIN mst_teachers t      ON c.teacher_id      = t.teacher_id
     JOIN mst_lesson_types lt ON c.lesson_type_id = lt.lesson_type_id
     WHERE c.id = :id'
);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);

$displayDate = date('Y年n月j日', strtotime($res['Lesson_date']));
$displayTime = date('H:i',       strtotime($res['Lesson_time']));
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>予約更新完了</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="card">
      <div class="success-message">
        <h3>✅ 予約が更新されました！</h3>
      </div>
      <div class="lesson-info">
        <h4>更新後の詳細</h4>
        <p><strong>講師：</strong> <?= h($res['teacher_name']) ?></p>
        <p><strong>種別：</strong> <?= h($res['lesson_type_name']) ?></p>
        <p><strong>日時：</strong> <?= $displayDate ?> <?= $displayTime ?></p>
      </div>
      <div style="text-align:center; margin-top:20px;">
        <a href="booking.php" class="btn">一覧へ戻る</a>
      </div>
    </div>
  </div>
</body>
</html>