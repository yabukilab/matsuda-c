<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品表示</title>
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

				$sql='SELECT * FROM mst_product WHERE code = :code';
				$stmt=$db->prepare($sql);
				$stmt->bindValue(':code', $pro_code, PDO::PARAM_INT);
				$stmt->execute();

				$dbh=null;

				$rec=$stmt->fetch(PDO::FETCH_ASSOC);

				if($rec==false)
				{
					print'商品がコードが正しくありません。';
					print'<a href="index.php">戻る</a>';
					print '<br />';
					exit();
				}
			}
			catch(Exception$e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}

		?>

		商品表示<br /><br />
		商品コード <?php print h($rec['code']); ?><br />
		商品名 <?php print h($rec['name']); ?><br />
		価格 <?php print h($rec['price']); ?><br /><br />

		<form>
		<input type="button" onclick="history.back()" value="戻る">
		</form>

	</body>
</html>