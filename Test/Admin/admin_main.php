
<?php

$errMessage = "";

session_start();

$dbname = $_SESSION['DBNAME'];

if(isset($_SESSION['DBNAME'])){

	$db['host'] = "localhost";
	$db['name'] = "Admin";
	$db['pass'] = "Admin11";
	$db['dbname'] = $dbname;

	$dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8', $db['host'], $db['dbname']);

	try{
		$pdo = new PDO($dsn, $db['name'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));

		$result = $pdo->query('SHOW TABLES');

		/*while ($re = $result->fetch(PDO::FETCH_ASSOC)) {
			var_dump($re);
		}*/

		$result_columns = 'Tables_in_'.$dbname;
	}catch(PDOException $e){
		print($e->getMessage());
	}
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
        	<li><a href="admin_selectdb.php">データベース選択</a></li>
            <li><a href="admin_createTable.php">テーブル追加</li>
            <li><a href="#">データ削除</a></li>
        </ul>

        <h1>データベース<?php echo $dbname;?>のテーブル一覧</h1>

        <center>
        <fieldset style="width:50%" >
        <table border="1" bordercolor="red" bgcolor="white">
            <tr><th>テーブル名</th></tr>
 
            <?php 
            foreach($result as $row){
            ?> 
            <tr> 
                <td><a href="<?php echo "$pathToRoot"; ?>admin_select.php?table=<?php echo $row[$result_columns]; ?>"><?php echo htmlspecialchars($row[$result_columns]); ?></a></td>
            </tr> 
            <?php
            }
            ?>
        </table>
        </fieldset></center>



    </body>
</html> 