<?php
session_start();

// 確保 SESSION 內有用戶資料
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user'])) {
    die(json_encode(['success' => false, 'message' => '用戶未登入，請重新登入']));
}

$userData = $_SESSION['user'];
$username = $userData['user'];

// 資料庫連線
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => "資料庫連線失敗: " . $conn->connect_error]));
}

// 取得前端傳來的 JSON
$data = json_decode(file_get_contents('php://input'), true);

$startTime = $data['startTime'] ?? null;
$endTime = $data['endTime'] ?? null;
$applyType = $data['applyType'] ?? null; // 新增：接收 apply_type

// 檢查資料是否完整
if (empty($startTime) || empty($endTime) || empty($applyType) || empty($username)) {
    die(json_encode(['success' => false, 'message' => '請填寫完整的資料（時間與申請類型）']));
}

// 刪除原本該老師的資料
$deleteStmt = $conn->prepare("DELETE FROM set_time WHERE user = ?");
$deleteStmt->bind_param("s", $username);
$deleteStmt->execute();
$deleteStmt->close();

// 插入新資料（新增 apply_type 欄位）
$stmt = $conn->prepare("INSERT INTO set_time (user, open_time, close_time, apply_type) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $username, $startTime, $endTime, $applyType);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '資料庫錯誤：' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
