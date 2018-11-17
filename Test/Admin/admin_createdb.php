<?php

session_start();

$db['host'] = 'localhost';
$db['name'] = 'Admin';
$db['pass'] = 'Admin11';
$db['dbname'];

$message = '';
$errMessage='';


if(isset($_POST['createdb'])){
	$dbname = $_POST['dbname'];

	$dsn = sprintf('mysql: host=%s charset=utf8', $db['host']);

	try{
		$createdb_sql = 'CREATE DATABASE '. $dbname;

		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$result = $pdo->query($createdb_sql);

		$_SESSION['DBNAME'] = $dbname;
		header('Location: admin_createTable.php');
		exit();



	}catch(PDOException $e){
		//print($e->getMessage());
		$errMessage = '作成できませんでした。';
		print($errMessage);
	}

}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>データベース作成</title>
</head>
<body>

<center>
	<h1>データベース作成</h1>
	<br>
	<fieldset style="width:500px">
		<legend>データベース作成</legend>
		<form id="db" name="db" action="" method="POST">
			新データベース名　<input type="text" name="dbname" value="" placeholder="名前を入力">
			<br>
			<input type="submit" name="createdb" value="作成">
		</form>
	</fieldset>
</center>

</body>
</html>	