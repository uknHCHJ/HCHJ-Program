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

if ($type == 'certifications') {
    // 使用 DISTINCT 過濾重複的 organization
    $sql = "SELECT DISTINCT organization FROM portfolio WHERE student_id = '$userId' AND category = '專業證照'";
    $result = mysqli_query($conn, $sql);

    $files = [];
    while ($row = mysqli_fetch_assoc($result)) {
        if (!empty($row['organization'])) { 
            $files[] = ['organization' => $row['organization']]; 
        }
    }
    header('Content-Type: application/json');
    echo json_encode($files, JSON_UNESCAPED_UNICODE);
    exit();
}
?>
