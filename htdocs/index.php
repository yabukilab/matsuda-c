<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/db.php';

// すでにログイン済みなら予約一覧へ
if (!empty($_SESSION['Login_number'])) {
    header('Location: booking.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ログイン</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header"><h1>🎹 ピアノレッスン予約システム</h1></div>
    <div class="card login-form">
      <h2>ログイン</h2>
      <form method="post" action="login.php">
        <div class="form-group">
          <label for="studentId">受講者番号（7桁）</label>
          <input type="text"
                 name="Login_number"
                 id="studentId"
                 required
                 pattern="\d{7}"
                 maxlength="7">
        </div>
        <div class="form-group">
          <label for="password">パスワード</label>
          <input type="password"
                 name="password"
                 id="password"
                 required
                 maxlength="50">
        </div>
        <button type="submit" class="btn">ログイン</button>
      </form>
    </div>
  </div>
</body>
</html>
