<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品入力</title>
	</head>
	<body>

		商品入力<br /><br />

		<form method="post" action="add_done.php">

		名前を入力してください。<br />
		<input type="text" name="name" style="width:100px"><br />
		価格を入力してください。<br />
		<input type="text" name="price" style="width:50px"><br /><br />

		<input type="button" onclick="history.back()" value="戻る">
		<input type="submit" value="追加">

		</form>

	</body>
</html>