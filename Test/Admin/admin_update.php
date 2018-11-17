<?php

session_start();

if(isset($_SESSION['DBNAME']) && isset($_SESSION['TABLENAME'])){
	$db['host'] = 'localhost';
	$db['name'] = 'Admin';
	$db['pass'] = 'Admin11';
	$db['dbname'] = $_SESSION['DBNAME'];
	$table = $_SESSION['TABLENAME'];

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	try{
		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$sql = "SHOW COLUMNS FROM ".$table;

		$result = $pdo->query($sql);

		$show_count = $result->rowCount();

		foreach ($result as $row) {
			$columns[] = $row;
		}
	}catch(PDOException $e){
		print($e->getMessage());
	}
}


if(isset($_POST['select'])){
	$column = $_POST['select_columns'];
	$keyword = $_POST['name'];

	if(!empty($column) && !empty($keyword)){

		if(!empty($_POST['just'])){
			$select_sql = "SELECT * FROM ". $table. " WHERE ". $column. " = '". $keyword. "'";
			$update_frame = 1;
		}else{
			$select_sql = "SELECT * FROM ". $table. " WHERE ". $column. " LIKE '%". $keyword. "%'";
			$update_frame = 0;
		}

		try{
			$result = $pdo->query($select_sql);

			foreach ($result as $row) {
				$rows[] = $row;
			}
		$_SESSION['column'] = $column;
		$_SESSION['keyword'] = $keyword;
		}catch(PDOException $e){
			print($e->getMessage());
		}
	}
}

if(isset($_POST['updateB'])){
	for ($i=1; $i <= $show_count ; $i++) { 
		$update_data[$i] = $_POST[$i];
	}
	$count2 = 0;
	$message = '';
	$errMessage = '';

	foreach ($columns as $row) {
		$count2++;
		if(is_int($_SESSION['keyword']) && is_int($update_data[$count2])){
			$update_sql = "UPDATE ". $table. " SET ". $row['Field']. " = ". $update_data[$count2]. " WHERE ". $_SESSION['column']. " = ". $_SESSION['keyword'];
		}else if(!is_int($_SESSION['keyword']) && is_int($update_data[$count2])){
			$update_sql = "UPDATE ". $table. " SET ". $row['Field']. " = ". $update_data[$count2]. " WHERE ". $_SESSION['column']. " = '". $_SESSION['keyword']. "'";
		}else if(is_int($_SESSION['keyword']) && !is_int($update_data[$count2])){
			$update_sql = "UPDATE ". $table. " SET ". $row['Field']. " = '". $update_data[$count2]. "' WHERE ". $_SESSION['column']. " = ". $_SESSION['keyword'];
		}else if(!is_int($_SESSION['keyword']) && !is_int($update_data[$count2])){
			$update_sql = "UPDATE ". $table. " SET ". $row['Field']. " = '". $update_data[$count2]. "' WHERE ". $_SESSION['column']. " = '". $_SESSION['keyword']. "'";
		}
		try{
			$update = $pdo->query($update_sql);
			$message = 'アップデートが完了致しました。';
		}catch(PDOException $e){
			//print($e->getMessage());
			$err[] = $row['Field'];
		}
	}
	if($err == ''){
		echo $message;
	}else{
		$errMessage = 'カラム　';
		foreach ($err as $error) {
			$errMessage .= $error. ",";
		}
		$errMessage .= ' に問題が有ります。';
		echo $errMessage;
	}
}

if(isset($_POST['back'])){
	$update_frame = 0;
}

?>




<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>アップデート</title>
	<style type="text/css">
            <!--
            #menu {
                list-style: none;
                display: flex;
            }

            #menu li {
                width: 33%;
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
            -->
        </style>
</head>
<body>
	<ul id="menu">
            <li><a href="admin_update.php">データアップデート</a></li>
            <li><a href="admin_insert.php">データ追加</a></li>
            <li><a href="#">データ削除</a></li>
        </ul>
    <?php
    if($update_frame == 0){
    ?>
	<center>
		<h1>データベース [<?php echo $db['dbname'];?>] ,テーブル [<?php echo $table; ?>] のアップデート</h1>
		<fieldset style="width:30%;">
			<legend>検索フォーム</legend>
		<form id="column" name="column" action="" method="POST">
			<select name="select_columns">
				<?php
				foreach ($columns as $row) {
					echo '<option value="', $row['Field'], '">', $row['Field'], '</option>';
				}
				?>
			</select>
			<label for="name"></label><input type="text" id="name" name="name" value="" placeholder="検索キーワード">
			<br>
			完全に一致<input type="checkbox" name="just" value="1" checked="checked">	
			<br>
			<input type="submit" id="select" name="select" value="検索">	
		</form>
		</fieldset>
	</center>
	<?php
	}
	?>

	<?php
	if($_POST['select']){
	?>

	<center>
		<fieldset>
			<legend>検索結果</legend>
			<table border="1" bordercolor="blue" bgcolor="white">
				<tr>
					<?php
					foreach ($columns as $row) {
					?>
					<th><?php echo htmlspecialchars($row['Field'], ENT_QUOTES, 'UTF-8'); ?></th>
					<?php
					}
					unset($row);
					?>
				</tr>

				<?php
				foreach ($rows as $select_row) {
				?>
				<tr>
					<?php
					foreach ($columns as $show_row) {
						$this_column = $show_row['Field'];
					?>
					<td><?php echo $select_row[$this_column] ?></td>
					<?php
					}
					?>
				</tr>
				<?php
				}
				?>

			</table>
		</fieldset>
	</center>
	<?php
	}
	?>

	<?php
	if($update_frame == 1){
	?>
	<center>
		<fieldset>
			<legend>更新データ</legend>
			<form id="update" name="update" action="" method="POST">
			<table border="1" bordercolor="red" bgcolor="white">
				<tr>
					<?php
					foreach ($columns as $row) {
					?>
					<th><?php echo htmlspecialchars($row['Field'], ENT_QUOTES, 'UTF-8'); ?></th>
					<?php
					}
					unset($row);
					?>
				</tr>

				<?php
				foreach ($rows as $select_row) {
				?>
				<tr>
					<?php
					$count = 0;
					foreach ($columns as $show_row) {
						$count++;
						$this_column = $show_row['Field'];
					?>
					<!--<td><?php echo $select_row[$this_column] ?></td>-->
					<td><input type="text" name="<?php echo $count; ?>" value="<?php echo $select_row[$this_column]; ?>" size="15"></td>
					<?php
					}
					?>
					<br>
				</tr>
				<?php
				}
				?>

			</table>
			<input type="submit" name="updateB" value="アップデート">
			<input type="submit" name="back" value="検索に戻る">
			</form>
		</fieldset>
	</center>

	<?php
	}
	?>


</body>
</html>