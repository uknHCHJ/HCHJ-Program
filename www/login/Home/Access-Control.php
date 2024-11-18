<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link){
    mysqli_query($link,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
    exit;
}

header('Content-Type: application/json');
// 執行查詢
$query = "SELECT user, Permissions FROM user";
$result = mysqli_query($link, $query);

// 檢查是否有結果
if (!$result) {
    echo "查詢失敗：" . mysqli_error($link);
    exit;
}

// 將查詢結果轉換成陣列
$students = array();
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

// 回傳 JSON 格式的資料

echo json_encode($students, JSON_UNESCAPED_UNICODE);

// 釋放結果集並關閉連接
mysqli_free_result($result);
mysqli_close($link);
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>