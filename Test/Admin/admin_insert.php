<?php
session_start();

$addcount = $_POST["addcount"];	

$dbname = $_SESSION['DBNAME'];
$tablename = $_SESSION['TABLENAME'];

$db['host'] = 'localhost';
$db['name'] = 'Admin';
$db['pass'] = 'Admin11';
$db['dbname'] = $dbname;

if(!empty($dbname) && !empty($tablename)){

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	try{
		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$sql = 'SHOW COLUMNS FROM '.$tablename;
		$show_result = $pdo->query($sql);

		foreach ($show_result as $row) {
			$rows[] = $row;
		}
		$column_count = count($rows);
		$rows_json = json_encode($rows);
	}catch(PDOException $e){
		print($e->getMessage());
	}
}

if(isset($_POST['insert'])){

	for ($i=0; $i<=$addcount; $i++) { 
		$add[] = $_POST['add'. $i];
	}

	foreach ($add as $value) {
		$count = 0;
		$req_sql1 = 'INSERT INTO '. $tablename. ' (';
		$req_sql2 = ') VALUES (';
		$req_sql3 = ')';
		foreach ($rows as $row) {
			if(empty($row['Extra'])){
				if($count == 0){
					$req_sql1 .= $row['Field'];
					$req_sql2 .= $value[$count];
				}
				else{
					$req_sql1 .= ', '.$row['Field'];
					$req_sql2 .= ', '. $value[$count];
				}
				$count++;
			}
		}
		$req_sql = $req_sql1. $req_sql2. $req_sql3;
		try{
			$insert_result = $pdo->query($req_sql);
		}catch(PDOException $e){
			print($e->getMessage());
		}
	}


}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>データ挿入</title>
	<style type="text/css">
	<!--
	#menu {
		list-style: none;
		display: flex;
	}

	#menu li {
		width: 50%;
		text-align: center;
		background-color: #333;
		height: 50px;
		line-height: 50px;
		margin-right: 2px;
	}

	#menu li a {
		text-decoration: none;
		color: #fff;
		font-weight: bold;
		padding: 20px;
	}
	fieldset {
		display: inline;
	}

	input#submit{
		width: 100px;
		font-size: 2em;
	}
	-->
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>

	let rows_js = <?php echo $rows_json; ?>;
	var add_count = 0;
	function addframe(){
		var table = document.getElementById("table");

		var row = table.insertRow(-1);
		add_count++;
		for (var i = 0; i < rows_js.length; i++) {
			var cell = row.insertCell(-1);
			cell.innerHTML = '<input type="text" name="add' + add_count + '[]" size="10">';
		}

		var $addcount = {"count" : add_count};

		console.log(add_count);

		return;

	}

	function formsend(){
		document.forms['insert_form'].elements['addcount'].value = add_count;
	}

	function removeframe(){
		var table = document.getElementById("table");

		var row = table.deleteRow(-1);
		add_count--;
		console.log(add_count);
	}

	function reset(){
		window.location.href = 'admin_insert.php';
	}
</script>
</head>
<body>
	<ul id="menu">
		<li><a href="admin_select.php">データ検索に戻る</a></li>
		<li><a href="#"></a>?</li>
	</ul>
	<h1>データベース　<?php echo $dbname; ?> ,テーブル　<?php echo $tablename; ?>  にデータを挿入</h1>

	<center>
		<fieldset>
			<legend>データ挿入</legend>
			<form name="insert_form" method="POST">
				<center>
					<table id="table" border="1" bordercolor="blue" bgcolor="white">
						<tr>
							<?php
							foreach ($rows as $row) {
								?>
								<th><?php echo htmlspecialchars($row['Field'], ENT_QUOTES, 'UTF-8'); ?></th>
								<?php
							}
							?>
						</tr>

						<tr>
							<?php
							$for_count = 0;
							foreach ($rows as $row) {
								if(empty($row['Extra'])){
									?>
									<th><input type="text" name="add<?php echo $for_count; ?>[]" size="10"></th>
									<?php
								}else{
									?>
									<th></th>
									<?php
								}
							}
							$for_count++;
							?>
						</tr>
					</table>
				</center>
				<div align="right">
					<br>
					<input type="hidden" name="addcount" value="">
					<input id="submit" type="submit" name="insert" value="データ追加実行" onclick="formsend()">
				</div>
			</form>
			<div align="center">
				<button onclick=addframe()>挿入データ追加</button>
				<button onclick=removeframe()>一行削除</button>
				<button onclick=reset()>リセット</button>
			</div>
		</fieldset>
	</center>
</body>
</html>