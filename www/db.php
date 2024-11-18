<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ"; 

// 建立資料庫連線
$link = new mysqli($servername, $username, $password, $dbname);


// 檢查連線
if ($link){
    mysqli_query($link,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
    exit;
}
?>
