<?php
session_start();

if(isset($_SESSION["NAME"])){
	$errMessage = "ログアウトしました。";
}else{
	$errMessage = "セッションがタイムアウトしました。";
}

//セッションの変数のクリア
$_SESSION = array();

//セッションのクリア
@session_destroy();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>ログアウト</title>
	</head>
	<body>
		<h1>ログアウト画面</h1>
		<div><?php echo htmlspecialchars($errMessage, ENT_QUOTES); ?></div>
		<ul>
			<li><a href="Login.php">ログイン画面に戻る</a></li>
		</ul>
	</body>
</html>