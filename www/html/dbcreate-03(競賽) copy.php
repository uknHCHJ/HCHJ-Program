<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($conn){
    mysqli_query($conn,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
}

// 處理檔案上傳
if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
  $allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
  $filename = $_FILES['image']['name'];
  $filetype = $_FILES['image']['type'];
  $filesize = $_FILES['image']['size'];

  // 檢查檔案類型是否允許
  $ext = pathinfo($filename, PATHINFO_EXTENSION);
  if(!array_key_exists($ext, $allowed)) die("錯誤的檔案類型");

  // 檢查檔案大小是否過大
  $maxsize = 500000; // 500KB
  if($filesize > $maxsize) die("檔案太大");

  // 將檔案讀取為二進位資料
  $data = file_get_contents($_FILES['image']['tmp_name']);

  // 將資料插入資料庫
  $sql = "INSERT INTO information (image) VALUES (?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("b", $data);
  $stmt->execute();
}

// 關閉資料庫連線
$conn->close();
?>