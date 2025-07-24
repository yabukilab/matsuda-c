<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ログインエラー</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header"><h1>🎹 ピアノレッスン予約システム</h1></div>
    <div class="card error-card">
      <h3>ログインエラー</h3>
      <p>受講者番号またはパスワードが正しくありません。</p>
      <a href="index.php" class="btn">戻る</a>
    </div>
  </div>
</body>
</html>
