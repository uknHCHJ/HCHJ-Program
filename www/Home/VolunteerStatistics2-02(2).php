<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null; // 檢查 session 是否有效
$username = $userData['name'] ?? null;
$grade = $userData['grade'];
$class = $userData['class'];

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


// 抓取資料，修改 SQL 查詢，將每位學生獨立列出

$sql = "SELECT user, name FROM user WHERE class='$class' AND grade='$grade'";
$result = $conn->query($sql);

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = []; // 確保回傳空陣列而不是 null
}

// 回傳 JSON
header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>