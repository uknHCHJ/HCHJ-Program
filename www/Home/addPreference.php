<?php
session_start();
$servername = "127.0.0.1"; // 伺服器 IP 或本地端 localhost
$username = "HCHJ"; // 登入帳號
$password = "xx435kKHq"; // 密碼
$dbname = "HCHJ"; // 資料表名稱

// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userId = $userData['user']; // 根據您實際的 session 結構獲取用戶 ID

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]));
}
// 檢查是否有登入
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '尚未登入']);
    exit();
}

$student_user = $_SESSION['user']; // 取得登入的學生帳號

// 獲取從前端傳來的 JSON 資料
$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['preferences'])) {
    echo json_encode(['success' => false, 'message' => '沒有提供志願資料']);
    exit();
}

$preferences = $data['preferences'];
$created_at = date('Y-m-d H:i:s');

$sql = "INSERT INTO preferences (student_user, Secondskill_id, school_name, department_id, department_name, preference_rank, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => '準備語句失敗: ' . $conn->error]);
    exit();
}

foreach ($preferences as $preference) {
    $school_id = $preference['school_id'];
    $department_id = $preference['department_id'];
    $preference_rank = $preference['serial_number'];

    // 查詢學校名稱和科系名稱
    $school_name_query = "SELECT school_name FROM School_Department WHERE school_id = ?";
    $school_stmt = $conn->prepare($school_name_query);
    $school_stmt->bind_param("i", $school_id);
    $school_stmt->execute(); 
    $school_stmt->bind_result($school_name);
    $school_stmt->fetch();
    $school_stmt->close();

    $department_name_query = "SELECT department_name FROM School_Department WHERE department_id = ?";
    $department_stmt = $conn->prepare($department_name_query);
    $department_stmt->bind_param("i", $department_id);
    $department_stmt->execute();
    $department_stmt->bind_result($department_name);
    $department_stmt->fetch();
    $department_stmt->close();

    // 執行插入語句
    $stmt->bind_param("sisssis", $student_user, $school_id, $school_name, $department_id, $department_name, $preference_rank, $created_at);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => '插入資料失敗: ' . $stmt->error]);
        exit();
    }
}

$stmt->close();
$conn->close();

echo json_encode(['success' => true]);
?>
