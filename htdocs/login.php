<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
</head>
<body>
<link rel="stylesheet" href="login.css">
<div class="header">
    <h1>在庫管理システム</h1>
</div>

<div class="login-form">
    <h2>IDとパスワードを入力してください。</h2>

    <form method="post" action="login_check.php">
        <label for="code">スタッフコード</label>
        <input type="text" id="code" name="code" required><br><br>

        <label for="pass">パスワード</label>
        <input type="password" id="pass" name="pass" required><br><br>

        <input type="submit" value="ログイン">
    </form>
</div>

</body>
</html>
