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

$adm_pk=$_GET['school_id'];

$sql ="DELETE FROM School WHERE school_id = '".$adm_pk."'";

if ($conn->query($sql) === TRUE) {
  echo "<script>
  alert('刪除成功');
  window.location.href = 'portfolio delete-03(編輯).php';
</script>";
  } else {
    echo "<script>
  alert('刪除失敗');
  window.location.href = 'portfolio delete-03(編輯).php';
</script>";
  }
  //關閉資料庫
  $conn->close();


?>