<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null;
$username = $userData['name'] ?? null;

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

// 檢查連線
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// 調整 SQL 查詢
$sql = "SELECT 
            p.school_name AS School, 
            p.department_name AS Department, 
            COUNT(u.name) AS StudentCount, 
            GROUP_CONCAT(u.name SEPARATOR ', ') AS Students  
        FROM preferences p
        JOIN user u ON p.student_user = u.user  
        GROUP BY p.school_name, p.department_name  
        ORDER BY p.school_name, p.department_name";

$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = [];
}

// 回傳 JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
