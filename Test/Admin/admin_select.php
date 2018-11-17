<?php

$errMessage = "";

session_start();

if(isset($_GET['table'])){
	$table = $_GET['table'];
}else{
	$table = "";
}

if(!empty($table)){

	$db['host'] = 'localhost';
	$db['name'] = 'Admin';
	$db['pass'] = 'Admin11';
	$db['dbname'] = $_SESSION['DBNAME'];

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	try{
		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$show_sql = 'SHOW COLUMNS FROM '.$table;
		$show_result = $pdo->query($show_sql);

		$select_sql = 'SELECT * FROM '.$table;
		$select_result = $pdo->query($select_sql);

		$_SESSION['TABLENAME'] = $table;

		$show_count = $show_result->rowCount();

		foreach ($select_result as $row) {
			$rows[] = $row;
		}
		$def_rows[] = $rows;
		foreach ($show_result as $row) {
			$rows2[] = $row;
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
		}else{
			$select_sql = "SELECT * FROM ". $table. " WHERE ". $column. " LIKE '%". $keyword. "%'";
		}

		try{
			$result = $pdo->query($select_sql);
			unset($rows);
			foreach ($result as $row) {
				$rows[] = $row;
			}
		}catch(PDOException $e){
			print($e->getMessage());
		}
	}
	$select = 1;
}

if(isset($_POST['reset'])){
	$rows[] = $def_rows;
	$select = 0;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>テーブル一覧</title>
        <style type="text/css">
            <!--
            #menu {
                list-style: none;
                display: flex;
            }

            #menu li {
                width: 25%;
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
        	<li><a href="admin_main.php">テーブル選択に戻る</a></li>
            <li><a href="admin_update.php">データアップデート</a></li>
            <li><a href="admin_insert.php">データ追加</a></li>
            <li><a href="#">データ削除</a></li>
        </ul>

        <h1>データベース<?php echo $db['dbname'];?>のデータ一覧</h1>

        <center>
        	<fieldset style="width:30%;">
			<legend>検索フォーム</legend>
		<form id="column" name="column" action="" method="POST">
			<select name="select_columns">
				<?php
				foreach ($rows2 as $row) {
					echo '<option value="', $row['Field'], '">', $row['Field'], '</option>';
				}
				?>
			</select>
			<label for="name"></label><input type="text" id="name" name="name" value="" placeholder="検索キーワード">
			<br>
			完全に一致<input type="checkbox" name="just" value="1">	
			<br>
			<input type="submit" id="select" name="select" value="検索">	
		</form>
		<?php if($select == 1){ ?>
		<form method="POST"><input type="submit" name="reset" value="一覧に戻る"></form>
		<?php } ?>
		</fieldset>
		<br>

        <fieldset style="width:50%" >
        <table border="1" bordercolor="red" bgcolor="white">
            <tr>
            	<?php
            	$count = 0;
            	foreach ($rows2 as $row) {
            	?>
            	<th><?php echo htmlspecialchars($row['Field'], ENT_QUOTES, 'UTF-8'); ?></th>
            	<?php
            	}
            	unset($row);
            	?>
            </tr>
 
            <?php
            foreach($rows as $select_row){
            ?> 
            <tr>
            	<?php
            	foreach ($rows2 as $show_row) {
            		$column = $show_row['Field'];
            	?>
            	<td><?php echo $select_row[$column]; ?></td>
            	<?php
            	}
            	?>
            </tr>	
            <?php
            }
            ?>
        </table>
        </fieldset></center>



    </body>
</html> 