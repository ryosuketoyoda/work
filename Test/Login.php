<?php
require 'password.php';


//セッション開始
session_start();

$db	['host'] = 'localhost';		//DBサーバーのURL
$db ['user'] = 'connecter';		//ユーザー名
$db ['pass'] = 'Inoriguilty_11';//ユーザーのパスワード
$db ['dbname'] = 'loginManagement';	//接続用データベースの名前

//エラーメッセージの初期化
$errMessage = "";

//ログインボタンが押された時
if(isset($_POST["login"])){
	//1.ユーザーネームのチェック
	if(empty($_POST["username"])){
		$errMessage = 'ユーザーネームが未入力です。';
	}else if(empty($_POST["password"])){
		$errMessage = 'パスワードを入力して下さい。';
	}

	if(!empty($_POST["username"]) && !empty($_POST["password"])){
		//入力したユーザーネームを格納
		$username = $_POST["username"];
		//2. ユーザーネームとパスワードが入力されていたら認証する。
		$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);
		//3. エラー処理
		try{
			$pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

			$stmt = $pdo->prepare('SELECT * FROM userData where name = ?');
			$stmt->execute(array($username));

			$password = $_POST["password"];

			if($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				if(password_verify($password, $row['password'])){
					session_regenerate_id(true);
					//入力したユーザー名を取得
					$id = $row['id'];
					$sql = "SELECT * FROM userData WHERE id = $id";//入力したIDからユーザー名を取得
					$stmt = $pdo->query($sql);
					foreach ($stmt as $row) {
						$row['name']; //ユーザー名
						$row['id'];
					}
					$_SESSION["NAME"] = $row['name'];
					$_SESSION["PASSWORD"] = $row['password'];
					$_SESSION["ID"] = $row['id'];
					header("Location: mainmenu.php");	//メイン画面へ遍移
					exit();	//処理終了
				}else{
					// 認証失敗
					$errMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
				}
			}else{
				//4. 認証成功ならセッションIDを新規に発行する。
				//該当データなし
				$errMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
			}
		}catch(PDOException $e){
			$errMessage = 'データベースエラー';
			//$errMessage = $sql;
			//$e->getMessage(); //でエラー内容を参照可能(デバッグ時のみ表示)
			//echo $e->getMessage();
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
		<form action="SignUp.php">
			<fieldset style="width:200px;height:50px">
				<legend>新規登録フォーム</legend>
				<input type="submit" value="新規登録">
			</fieldset>
		</form>
	</center>
</body>
</html>
