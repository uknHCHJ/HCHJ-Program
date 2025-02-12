<?php
header('Content-Type: application/json; charset=utf-8');

$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 連接 MySQL 資料庫
$conn = mysqli_connect($servername, $username, $password, $dbname);

session_start();

if (!isset($_SESSION['user'])) {
    echo json_encode([]);
    exit();
}

$type = isset($_GET['type']) ? $_GET['type'] : '';
$userId = $_SESSION['user']['user'];

if ($type == 'autobiography') {
    $sql = "SELECT file_name FROM portfolio WHERE student_id = '$userId' AND category = '自傳'";
    $result = mysqli_query($conn, $sql);

    $files = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // 正確使用欄位名稱 file_name
        $files[] = ['filename' => $row['file_name']];
    }

    echo json_encode($files, JSON_UNESCAPED_UNICODE);
    exit();
}

echo json_encode([]);
?>
