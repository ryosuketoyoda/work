<?php

$errMessage = "";

$login_username = "Admin";
$login_password = "EGOIST";

if(isset($_POST["login"])){

	if(empty($_POST["username"])){
		$errMessage = "名前を入力してください";
	}else if(empty($_POST["password"])){
		$errMessage = "パスワードを入力してください";
	}
	if(!empty($_POST["username"]) && !empty($_POST["password"])){
		$username = $_POST["username"];
		$pass = $_POST["password"];

		if($username == $login_username && $pass == $login_password){
			header("Location: admin_selectdb.php");
			exit();
		}else{
			$errMessage = "名前またはパスワードに誤りがあります。";
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>ログイン</title>
</head>
<body>
	<center>
		<h1>ログイン画面</h1>
		<form id="loginForm" name="loginForm" action="" method="POST">
			<fieldset style="width:500px;height:200px; border: solid 1px;">
				<legend>ログインフォーム</legend>
				<div style="margin:5px"><font color="#ff0000"><?php echo htmlspecialchars($errMessage, ENT_QUOTES);?></font></div>
				<div style="margin:40px 10px 0px 0px"><label for="username">ユーザーネーム</label><input type="text" id="username" name="username" placeholder="ユーザーネームを入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>"></div>
				<br>
				<label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
				<br>
				<div style="margin:30px"><input type="submit" id="login" name="login" value="ログイン"></div>
			</fieldset>
		</form>
		<br>
	</center>
</body>
</html>
