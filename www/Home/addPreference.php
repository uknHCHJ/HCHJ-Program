<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 檢查 Session 是否有效
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user'];

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]);
    exit();
}

// 獲取前端傳送的資料
$data = json_decode(file_get_contents("php://input"), true);
if ($data === null || !isset($data['Preferences'])) {
    echo json_encode(['success' => false, 'message' => '無法解析輸入的 JSON 或缺少 Preferences']);
    exit();
}

// **日誌記錄** (可選) - 檢查收到的 JSON 是否正確
file_put_contents("debug.log", print_r($data, true), FILE_APPEND);

// **刪除使用者現有的志願資料**
$sqldel = "DELETE FROM Preferences WHERE student_user = ?";
$delStmt = $conn->prepare($sqldel);
if ($delStmt === false) {
    echo json_encode(['success' => false, 'message' => '準備刪除語句失敗: ' . $conn->error]);
    exit();
}
$delStmt->bind_param("s", $userId);
$delStmt->execute();
$delStmt->close();

// **插入新的志願資料**
$sql = "INSERT INTO Preferences (student_user, school_name, department_name, preference_rank, created_at) 
        VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => '準備插入語句失敗: ' . $conn->error]);
    exit();
}

$success = true;
$response = [];

foreach ($data['Preferences'] as $preference) {
    // **確保必要字段存在且非空**
    if (!isset($preference['school_name']) || empty(trim($preference['school_name'])) ||
        !isset($preference['department_name']) || empty(trim($preference['department_name'])) ||
        !isset($preference['serial_number'])) {
        $success = false;
        $response['message'] = '缺少必要的字段或有空值';
        break;
    }

    // **清理輸入數據**
    $school_name = trim($preference['school_name']);
    $department_name = trim($preference['department_name']);
    $preference_rank = intval($preference['serial_number']);

    // **執行 SQL 插入**
    $stmt->bind_param("sssi", $userId, $school_name, $department_name, $preference_rank);
    if (!$stmt->execute()) {
        $success = false;
        $response['message'] = '插入資料失敗: ' . $stmt->error;
        break;
    }
}

$stmt->close();
$conn->close();

$response['success'] = $success;
if ($success) {
    $response['message'] = '所有資料已成功儲存';
}
echo json_encode($response);
?>
