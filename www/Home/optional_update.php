<?php
session_start();

$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 確保 SESSION 中有用戶信息
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user'];

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]));
}

// 設置編碼
$conn->set_charset("utf8");

// 獲取 POST 資料
$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(['success' => false, 'message' => '無法解析輸入的 JSON']);
    exit();
}

// 開啟交易
$conn->begin_transaction();

try {
    // 更新或插入資料
    $sqlUpdate = 
        'INSERT INTO Preferences (user, serial_number, school_id, department_id)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        school_id = VALUES(school_id),
        department_id = VALUES(department_id)';
    $stmt = $conn->prepare($sqlUpdate);

    foreach ($data['preferences'] as $preference) {
        $serial_number = $preference['serial_number'];
        $school_id = $preference['school_id'];
        $department_id = $preference['department_id'];

        // 綁定參數
        $stmt->bind_param("iiii", $userId, $serial_number, $school_id, $department_id);
        if (!$stmt->execute()) {
            throw new Exception("更新或插入資料失敗: " . $stmt->error);
        }
    }
    $stmt->close();

    // 提交交易
    $conn->commit();

    echo json_encode(['success' => true, 'message' => '變更已成功保存']);
} catch (Exception $e) {
    // 發生錯誤時回滾
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => '操作失敗: ' . $e->getMessage()]);
}

// 關閉連線
$conn->close();
?>
