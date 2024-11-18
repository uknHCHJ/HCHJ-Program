<?php
//設定變數來存放要接收的資料
$name = $_POST["name"];
//echo $name;
$in = $_POST["inform"];
$lk = $_POST["link"];

$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ"; 
$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die("連線錯誤" . mysqli_connect_error());
}

  $result = $conn->query("SELECT * FROM information ORDER BY ID ASC;");
  if (!$result) {
    die($conn->error);
  }

  while ($row = $result->fetch_assoc()) {
    echo 'name: ' . $row['name'] . '<br>';
    echo '<a href="delete-03(競賽).php?ID=' . $row['ID'] . '">刪除</a>';
    echo '<br>';
  }
 
?>
