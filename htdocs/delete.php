<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品削除</title>
	</head>
	<body>
		<?php

			require_once '_database_conf.php';
			require_once '_h.php';

			$pro_code=$_GET['procode'];

			try
			{
				$db = new PDO($dsn, $dbUser, $dbPass);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

				$sql='DELETE FROM mst_product WHERE code = :code';
				$stmt=$db->prepare($sql);
				$stmt->bindValue(':code', $pro_code, PDO::PARAM_INT);
				$stmt->execute();

				$db=null;

				print '削除しました。<br />';

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