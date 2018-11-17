<?php

session_start();

$errMessage;

if(isset($_POST["login"])){
	if(empty($_POST["dbname"])){
		$errMessage = "データベース名を入力してください。";
	}

	if(!empty($_POST["dbname"])){
		$db['host'] = "localhost";
		$db['name'] = "Admin";
		$db['pass'] = "Admin11";
		$db['dbname'] = $_POST["dbname"];

		$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

		try{
			$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

			header('Location: admin_main.php');
			$_SESSION['DBNAME'] = $db['dbname'];
			exit();


		}catch(PDOException $e){
			$errMessage = '入力したデータベース名が存在しません。確認してもう一度お試しください。';
			//print($e->getMessage());
		}
	}
}


?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>接続データベース選択</title>
</head>

<body>
	<center>
		<h1>データベース選択</h1>
		<form id="loginForm" name="loginForm" action="" method="POST">
			<fieldset style="width:500px;height:200px; border: solid 1px;">
				<legend>選択</legend>
				<div style="margin:5px"><font color="#ff0000"><?php echo htmlspecialchars($errMessage, ENT_QUOTES);?></font></div>
				<div style="margin:40px 10px 0px 0px"><label for="dbname">データベースネーム</label><input type="text" id="dbname" name="dbname" placeholder="データベース名" value="<?php if (!empty($_POST["dbname"])) {echo htmlspecialchars($_POST["dbname"], ENT_QUOTES);} ?>"></div>
				
				<div style="margin:30px"><input type="submit" id="login" name="login" value="次へ"></div>
			</fieldset>
		</form>
		<br>
		<form action="admin_createdb.php">
			<fieldset style="width:200px;height:50px">
				<legend>データベース新規作成</legend>
				<input type="submit" value="新規作成">
			</fieldset>
		</form>
	</center>
</body>
</html>