<?php
session_start();

$servername = "127.0.0.1"; // 伺服器 IP 或本地端 localhost
$username = "HCHJ"; // 登入帳號
$password = "xx435kKHq"; // 密碼
$dbname = "HCHJ"; // 資料表名稱

// 確保 SESSION 中儲存了唯一識別符
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user']; // 根據 SESSION 結構取得用戶 ID

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
    // 刪除舊資料
    $sqldel = "DELETE FROM Preferences WHERE user = ?";
    $delStmt = $conn->prepare($sqldel);
    $delStmt->bind_param("i", $userId);
    $delStmt->execute();
    $delStmt->close();

    // 插入新資料
    $sqlInsert = "INSERT INTO Preferences (user, serial_number, school_id, department_id) VALUES (?, ?, ?, ?)";
    $insertStmt = $conn->prepare($sqlInsert);

    foreach ($data['preferences'] as $preference) {
        $serial_number = $preference['serial_number'];
        $school_id = $preference['school_id'];
        $department_id = $preference['department_id'];

        $insertStmt->bind_param("iiii", $userId, $serial_number, $school_id, $department_id);
        $insertStmt->execute();
    }
    $insertStmt->close();

    // 提交交易
    $conn->commit();

    echo json_encode(['success' => true, 'message' => '變更已成功保存']);
} catch (Exception $e) {
    // 發生錯誤時回滾
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => '變更保存失敗: ' . $e->getMessage()]);
}

// 關閉連線
$conn->close();
?>
