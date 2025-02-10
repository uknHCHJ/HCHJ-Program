<?php 
session_start();

// 檢查是否已登入
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userId = (int)$_SESSION['user']; // 確保為數字 ID

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]));
}

// 獲取前端傳來的資料
$data = json_decode(file_get_contents("php://input"), true);

// 檢查是否有提供有效的志願資料
if (empty($data['preferences'])) {
    echo json_encode(['success' => false, 'message' => '沒有提供志願資料']);
    exit();
}

$preferences = $data['preferences'];
$created_at = date('Y-m-d H:i:s');

// 準備 SQL 插入語句
$sql = "INSERT INTO preferences (student_user, Secondskill_id, school_name, department_id, department_name, preference_rank, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => '準備語句失敗: ' . $conn->error]);
    exit();
}

// 處理每個志願資料
foreach ($preferences as $pref) {
    $school_id = (int)$pref['Secondskill_id'];
    $department_id = (int)$pref['department_id'];
    $preference_rank = (int)$pref['serial_number'];

    // 查詢學校名稱
    $school_stmt = $conn->prepare("SELECT school_name FROM School_Department WHERE school_id = ?");
    $school_stmt->bind_param("i", $school_id);
    $school_stmt->execute();
    $school_stmt->bind_result($school_name);
    $school_stmt->fetch();
    $school_stmt->close();

    if (empty($school_name)) {
        error_log("無法找到對應的 school_name，school_id: " . $school_id);
        continue;
    }

    // 查詢科系名稱
    $department_stmt = $conn->prepare("SELECT department_name FROM School_Department WHERE department_id = ?");
    $department_stmt->bind_param("i", $department_id);
    $department_stmt->execute();
    $department_stmt->bind_result($department_name);
    $department_stmt->fetch();
    $department_stmt->close();

    if (empty($department_name)) {
        error_log("無法找到對應的 department_name，department_id: " . $department_id);
        continue;
    }

    // 插入資料
    $stmt->bind_param("sisssis", $userId, $school_id, $school_name, $department_id, $department_name, $preference_rank, $created_at);
    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => '插入資料失敗: ' . $stmt->error]);
        exit();
    }
}

// 關閉語句和資料庫連線
$stmt->close();
$conn->close();

echo json_encode(['success' => true, 'message' => '資料儲存成功']);
?>
