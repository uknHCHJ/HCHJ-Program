<?php

$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱
//建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

//確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}
//echo "連線成功";
$ID=$_GET['ID'];
$name=$_POST["name"];
$ln=$_POST["inform"];
$lk=$_POST["link"];
$images=$_POST["imgname"];

$sql = "UPDATE information SET name = '".$name."', inform = '".$ln."',link = '".$lk."',imgname = '".$images."' WHERE ID = '".$ID."'";
//$sql = "UPDATE `information` SET `name` = '$adm_name', `inform` = '$adm_account', `link` = '$adm_link' WHERE `information`.`ID` = $ID;"

if ($conn->query($sql) === TRUE) {
    echo "更新成功";
    header("location:ContestEdin1-04.php?pk=".$ID);
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }


  //關閉資料庫
  $conn->close();


?>