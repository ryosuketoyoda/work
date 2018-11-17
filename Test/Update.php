<?php
$rowsLimit = 100;

if(isset($_GET['start']) == false){
	$start = 0;
}else{
	$start = $_GET['start'];
}

$last = $start + $rowsLimit;
?>


<?php

session_start();

if(!isset($_SESSION['NAME'])){
	header("Location: Logout.php");
	exit();
}

if(isset($_SESSION['NAME']) && isset($_SESSION['PASSWORD']) && isset($_SESSION['ID'])){
	$db['host'] = 'localhost';
	$db['user'] = 'connecter';
	$db['password'] = 'Inoriguilty_11';
	$db['dbname'] = 'userdb';

	$errorMessage = '';

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	$userid = $_SESSION['ID'];

	try{
		$pdo = new PDO($dsn, $db['user'], $db['password'], array(PDO::ATTR_ERRMODE=>ERRMODE_EXCEPTION));

		$sql = "SELECT * FROM `{$userid}` ORDER BY id LIMIT ". $start. ", ". $rowsLimit;
		$sql_count = "SELECT * FROM `{$userid}`";

		$result = $pdo->query($sql);
		$count_result = $pdo->query($sql_count);

		$row_count = $count_result->rowCount();

		foreach ($result as $row) {
			$rows[] = $row;
		}

	}catch(PDOException $e){
		$errorMessage = 'データベースエラー';
		echo $errorMessage;
		print('Error:' . $e->getMessage());
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>データ更新</title>
		<style type="text/css">
		<!--
			fieldset{
				background-color: cyan;
			}
		-->
		</style>
	</head>
	<body>
		<h1>データ更新</h1>

		<center>
			<fieldset style="width:70%">
				<legend>所持チェッカー</legend>
				<table border="1" bordercolor="red" bgcolor="white">
					<tr><th>No.</th><th>艦種</th><th>艦娘名</th><th>所持</th></tr>
					<?php
					foreach ($rows as $row) {
					?>
					<tr>
						<td><?php echo $row['id']; ?></td>
						<th><?php echo htmlspecialchars($row['ship_type'],ENT_QUOTES, 'UTF-8'); ?></th>
						<th><?php echo htmlspecialchars($row['name'],ENT_QUOTES, 'UTF-8'); ?></th>
						<th><?php echo $row['get_ship']; ?></th>
					</tr>
					<?php
					}
					?>
					<span>
						<?php
						if($start > 0){
						?>
							<a href="<?php echo "$pathToRoot" ?>Update.php?start=<?php echo $start - $rowsLimit; ?>">前のページ</a>
						<?php
						}
						?>
					</span>
					<span>
						<?php
						if($last < $row_count){
						?>
							<a href="<?php echo "$pathToRoot" ?>Update.php?start=<?php echo $start + $rowsLimit; ?>">次のページ</a>
						<?php
						}
						?>
					</span>
					
				</table>
			</fieldset>
		</center>

	</body>
</html>