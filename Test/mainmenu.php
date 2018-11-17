<?php
//指定レコード数
$noOfRows = 100;

//このページの最初の行、なければ0
if(isset($_GET['start']) == false){
    $start=0;
}else{
    $start = $_GET['start'];
}

$last = $start + $noOfRows;
?>

<?php
session_start();

//ログイン状態チェック
if(!isset($_SESSION['NAME'])){
    header("Location: Logout.php");
    exit();
}

if(isset($_SESSION['NAME']) && isset($_SESSION['PASSWORD'])){
    $db['name'] = 'connecter';
    $db['password'] = 'Inoriguilty_11';
    $db['host'] = 'localhost';
    $db['dbname'] = 'kankore';

    $errorMessage = '';

    $username = $db['name'];

    $dsn = sprintf('mysql: host=%s; dbname=%s; charset=utf8' , $db['host'] , $db['dbname']);
    try{
        $pdo = new PDO($dsn, $db['name'], $db['password'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        
        $sql = 'SELECT id,ship_type,name FROM T_kankore ORDER BY id LIMIT '. $start. ', '. $noOfRows;
        $sql_count = 'SELECT * FROM T_kankore';

        $result = $pdo->query($sql);
        $count_result = $pdo->query($sql_count);

        $row_count = $count_result->rowCount();

        foreach ($result as $row) {
            $rows[] = $row;
        }

        $first_connect = $_SESSION['count'];
    }catch(PDOException $e){
        $errorMessage = 'データベースエラー';
        echo $errorMessage;
        print('Error:'.$e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>メイン</title>

        <style type="text/css">
            <!--
            fieldset{
                background-color: cyan;
            }
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
            <li><a href="Select.php">検索</a></li>
            <li><a href="Update.php">更新</a></li>
            <li><a href="#">MENU3</a></li>
            <li><a href="#">MENU4</a></li>
        </ul>
        <h1>メイン画面</h1>

        <center>
        <fieldset style="width:70%" >
            <legend>艦娘一覧</legend>
        <table border="1" bordercolor="red" bgcolor="white">
            <tr><th>No.</th><th>艦種</th><th>艦娘名</th></tr>
 
            <?php 
            foreach($rows as $row){
            ?> 
            <tr> 
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['ship_type'],ENT_QUOTES, 'UTF-8');?></td>
                <td><?php echo htmlspecialchars($row['name'],ENT_QUOTES,'UTF-8'); ?></td>
                
            </tr> 
            <?php
            }
            ?>
            <span>
                <?php
                if($start > 0){
                ?>
                    <a href="<?php echo "$pathToRoot" ?>mainmenu.php?start=<?php echo $start - $noOfRows; ?>">前のページ</a>
                <?php
                }
                ?>
            </span>
            <span>
                <?php
                if($last < $row_count){
                ?>
                    <a href="<?php echo "$pathToRoot" ?>mainmenu.php?start=<?php echo $start + $noOfRows; ?> ">次のページ</a>
                <?php
                }
                ?>
            </span>
        </table>
        </fieldset></center>
        <ul>
            <li><a href="Logout.php">ログアウト</a></li>
        </ul>
    </body>
</html> 