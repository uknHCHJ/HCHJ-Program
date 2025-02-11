<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $dbUser, $dbPassword, $dbname);

// 檢查連接是否成功
if (!$link) {
    $response[0] = "無法連接資料庫：" . mysqli_connect_error();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 抓取資料
$sql = "SELECT 
        CONCAT(school_name, ' - ', department_name) AS school_department,
        GROUP_CONCAT(student_user SEPARATOR ', ') AS students
    FROM preferences
    GROUP BY school_department
    ORDER BY school_name, department_name
";
$result = $conn->query($sql);

$data = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

// 回傳 JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
