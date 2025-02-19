<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null; // 抓取登入使用者的 user ID

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "資料庫連線失敗: " . $conn->connect_error]));
}

// 接收前端傳送的資料
$data = json_decode(file_get_contents('php://input'), true);

$startTime = $data['startTime'] ?? null;
$endTime = $data['endTime'] ?? null;

// 檢查資料是否完整
if (empty($startTime) || empty($endTime) || empty($userId)) {
    die(json_encode(['success' => false, 'message' => '請填寫完整的開始、結束時間，並確保已登入'])); 
}

// 使用預備語句 (Prepared Statement) 避免 SQL injection
$stmt = $conn->prepare("INSERT INTO set_time (user_id, open_time, close_time) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $userId, $startTime, $endTime); // 正確對應參數數量與型態

// 執行 SQL 語句
if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $stmt->error]);
}

// 關閉預備語句和資料庫連接
$stmt->close();
$conn->close();
?>
