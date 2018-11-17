<?php

session_start();

$data_type = array('int','float','varchar(34)','text','date','time');
$data_type_json = json_encode($data_type);

if(isset($_POST['createtable']) && isset($_SESSION['DBNAME'])){
	$db['host'] = 'localhost';
	$db['name'] = 'Admin';
	$db['pass'] = 'Admin11';
	$db['dbname'] = $_SESSION['DBNAME'];

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	$name = $_POST['columnName'];
	$type = $_POST['columnType'];
	$count = count($name);
	for ($i = 1; $i <= $count; $i++) { 
		$increment[] = $_POST['columnIncrement'. $i];
		$null[] = $_POST['columnNull'. $i];
	}
	$def = $_POST['columnDef'];

	$AI_index = "";

	try{
		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$req_sql = 'CREATE TABLE '. $_POST['tablename']. '(';

		for ($i=0; $i<$count; $i++) { 
			if(empty($increment[$i]) && empty($null[$i]) && empty($def[$i])){
				$sql = $name[$i]. " ". $type[$i];
			}else if(empty($increment[$i]) && empty($null[$i])){
				$sql = $name[$i]. " ". $type[$i].	' DEFAULT "'. $def[$i] .'"';
			}else if(empty($increment[$i]) && empty($def[$i])){
				$sql = $name[$i]. " ". $type[$i]. " NOT NULL";
			}else if(empty($null[$i]) && empty($def[$i])){
				$sql = $name[$i]. " ". $type[$i]. " AUTO_INCREMENT PRIMARY KEY";
				//$AI_index = 'INDEX('. $name[$i]. ')';
			}else if(empty($increment[$i])){
				$sql = $name[$i]. " ". $type[$i]. " NOT NULL DEFAULT '". $def[$i]. "'";
			}else if(empty($null[$i])){
				$sql = $name[$i]. " ". $type[$i]. " AUTO_INCREMENT DEFAULT '". $def[$i]. "' PRIMARY KEY";
				//$AI_index = 'INDEX('. $name[$i]. ')';
			}else if(empty($def[$i])){
				$sql = $name[$i]. " ". $type[$i]. " AUTO_INCREMENT NOT NULL PRIMARY KEY";
				$AI_index = 'INDEX('. $name[$i]. ')';
			}else{
				$sql = $name[$i]. " ". $type[$i]. " AUTO_INCREMENT NOT NULL DEFAULT '". $def[$i]. "' PRIMARY KEY";
				$AI_index = 'INDEX('. $name[$i]. ')';
			}

			if($i == 0){
				$req_sql .= $sql;
			}else{
				$req_sql .= ','. $sql;
			}
		}
		//$req_sql .= ','.$AI_index. ')';

		$result = $pdo->query($req_sql);

		header('Location: admin_main.php');
		exit();
	}catch(PDOException $e){
		print($e->getMessage());
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>テーブル作成</title>
	<script>
		var add_count = 2;
		let data_type = <?php echo $data_type_json; ?>;
		function addColumn(){
			console.log(add_count);
			var text = '<font>カラム' + add_count + '</font>';

			var name = '<input type="text" name="columnName[]" value="">';

			var selectType = '<select id=' + add_count + ' name=columnType[]></select>';

			var increment = 'INCREMENT<input type="checkbox" name="columnIncrement' + add_count + '">';

			var notnull = 'NOT NULL<input type="checkbox" name="columnNull' + add_count + '">';

			var def = 'Def<input type="text" name="columnDef[]">';

			var div_element = document.createElement("div");
			div_element.innerHTML = text + " " + name + " " + selectType + " " + increment + " " + notnull + " " + def + " ";
			var parent_object =  document.getElementById("addcolumn");
			parent_object.appendChild(div_element);

			for (var i = 0; i < data_type.length; i++) {
				let op = document.createElement("option");
				op.value = data_type[i];
				op.text = data_type[i];
				document.getElementById(add_count).appendChild(op);
			}
			add_count ++;
		}
		function reset(){
			window.location.href = 'admin_createTable.php';
		}
	</script>
	</script>
</head>
<body>
<center>
	<h1>テーブル作成</h1>
	<br>
	<fieldset style="width:700px">
		<legend>テーブル作成</legend>
		<form id="table" name="table" action="" method="POST">
			<font>テーブル名 </font><input type="text" name="tablename" value="" placeholder="名前を入力">
			<br><br>
			<font>カラム1 </font><input type="text" name="columnName[]">
			<select name="columnType[]"><?php
			foreach ($data_type as $type) {
				echo '<option value="', $type ,'">', $type, '</option>';
			}
			?></select>
			INCREMENT<input type="checkbox" name="columnIncrement1">
			NOT NULL<input type="checkbox" name="columnNull1">
			Def<input type="text" name="columnDef[]">

			<br>
			<div id=addcolumn></div>
		<input type="submit" name="createtable" value="作成">
		</form>
		<button onclick=addColumn()>カラム追加</button>
		<button onclick=reset()>リセット</button>
	</fieldset>
</center>
</body>
</html>