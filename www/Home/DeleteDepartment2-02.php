<?php
include 'header.php'; //引入表頭

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

$school_id= $_GET["school_id"];
//echo "連線成功";
$department_id=$_GET["department_id"];

$sql ="DELETE FROM Department WHERE department_id = '".$department_id."'";

if ($conn->query($sql) === TRUE) {
    echo "刪除成功";
    header("Location: SchoolDepartment-02.php?school_id=" . $school_id);
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  //關閉資料庫
  $conn->close();


?>