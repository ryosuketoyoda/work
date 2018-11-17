<?php

session_start();

$db['host'] = 'localhost';
$db['user'] = 'connecter';
$db['pass'] = 'Inoriguilty_11';
$db['dbname'] = 'userdb';

$errorMessage = "";

if(isset($_SESSION['LOGIN_ID'])){
	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	$login_id = $_SESSION['LOGIN_ID'];

	try{
		$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$stmt = $pdo->query("CREATE TABLE `{$login_id}` LIKE tableDef");

		$stmt = $pdo->query("INSERT INTO `{$login_id}` SELECT * FROM tableDef");
		header("Location: Login.php");

	}catch(PDOException $e){
		print('Error:'.$e->getMessage());
		$errorMessage = $login_id;
		echo $errorMessage;
		unset($_SESSION['LOGIN_ID']);
	}
}
exit();



?>




<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php echo "aaa"; ?>
<?php echo $errorMessage; ?>
</body>
</html>