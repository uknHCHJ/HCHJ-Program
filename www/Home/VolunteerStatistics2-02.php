<?php
session_start();

// 檢查使用者是否登入
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// 使用者資料
$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// 抓取資料
$sql = "SELECT 
        CONCAT(school_name, ' - ', department_name) AS school_department,
        GROUP_CONCAT(student_user SEPARATOR ', ') AS students
    FROM preferences
    GROUP BY school_name, department_name
    ORDER BY school_name, department_name";

$result = $conn->query($sql);

// 處理結果
$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    echo json_encode(['error' => 'No data found']);
    $conn->close();
    exit;
}

// 回傳 JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($data);

// 關閉資料庫連線
$conn->close();
?>
