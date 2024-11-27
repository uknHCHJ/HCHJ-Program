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
    $ID=$_GET['school_id'];
    $name = $_POST["school_name"];
    $location = $_POST['location'];
    $in = $_POST["inform"];
    $lk = $_POST["link"];
    $school_id= $_POST["school_id"];

$sql = "UPDATE School SET school_name = '".$name."',location = '".$location."',inform = '".$in."',link = '".$lk."' WHERE school_id = '".$ID."'";

if ($conn->query($sql) === TRUE) {
  echo "刪除成功";
    header("Location:SchoolUpdate1.php?school_id=" . $ID);
  }else{
    echo "刪除失敗";
    header("Location:SchoolUpdate1.php?school_id=" . $ID);
  }
  $conn->close();

?>