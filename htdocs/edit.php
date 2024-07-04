<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>商品修正</title>
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

				$pro_name = $rec['name'];
				$pro_price = $rec['price'];

			}
			catch(Exception $e)
			{
				echo 'エラーが発生しました。内容: ' . h($e->getMessage());
	 			exit();
			}

		?>

		商品修正<br />
		<br />

		<form method="post" action="edit_done.php">

		商品コード <?php print $pro_code; ?><br />
		<input type="hidden" name="code" value="<?php print $pro_code; ?>"><br />

		商品名<br />
		<input type="text" name="name" style="width:200px" value="<?php print $pro_name; ?>"><br />
		価格<br />
		<input type="text" name="price" style="width:50px" value="<?php print $pro_price; ?>">円<br /><br />

		<input type="button" onclick="history.back()" value="戻る">
		<input type="submit" value="ＯＫ">

		</form>

	</body>
</html>