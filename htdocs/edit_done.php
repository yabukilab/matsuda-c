<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>DB登録</title>
	</head>
	<body>
		<?php

			require_once '_database_conf.php';
			require_once '_h.php';

			$pro_code=$_POST['code'];
			$pro_name=$_POST['name'];
			$pro_price=$_POST['price'];

			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='UPDATE mst_product SET name=:name,price=:price WHERE code=:code';
				$stmt=$db->prepare($sql);
				$stmt->bindValue(':name', $pro_name, PDO::PARAM_STR);
				$stmt->bindValue(':price', $pro_price, PDO::PARAM_INT);
				$stmt->bindValue(':code', $pro_code, PDO::PARAM_INT);
				$stmt->execute();

				$db=null;

				print '修正しました。<br />';

			}
			catch(Exception$e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}

		?>
		<a href="index.php">戻る</a>
	</body>
</html>