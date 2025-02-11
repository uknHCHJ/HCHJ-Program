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

// 檢查連線是否成功
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]);
    exit();
}

// 獲取前端傳送的資料
$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(['success' => false, 'message' => '無法解析輸入的 JSON']);
    exit();
}

// 刪除使用者現有的志願資料
$sqldel = "DELETE FROM preferences WHERE student_user = ?";
$delStmt = $conn->prepare($sqldel);
if ($delStmt === false) {
    echo json_encode(['success' => false, 'message' => '準備刪除語句失敗: ' . $conn->error]);
    exit();
}
$delStmt->bind_param("i", $userId);
if (!$delStmt->execute()) {
    echo json_encode(['success' => false, 'message' => '刪除資料失敗: ' . $delStmt->error]);
    exit();
}
$delStmt->close();

// 插入新的志願資料
$sql = "INSERT INTO preferences (student_user, Secondskill_id, school_name, department_id, department_name, preference_rank, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => '準備插入語句失敗: ' . $conn->error]);
    exit();
}

$success = true;
$response = [];
foreach ($data['preferences'] as $preference) {
    $Secondskill_id = $preference['Secondskill_id'];
    $school_name = $preference['school_name'];
    $department_id = $preference['department_id'];
    $department_name = $preference['department_name'];
    $preference_rank = $preference['serial_number'];

    $stmt->bind_param("iisssi", $userId, $Secondskill_id, $school_name, $department_id, $department_name, $preference_rank);
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
