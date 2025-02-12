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
$userId = $userData['user'];  // 確認這裡是正確的 user ID

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
    // 先刪除原有的資料
    $sqlDelete = "DELETE FROM preferences WHERE student_user = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    if ($stmtDelete === false) {
        throw new Exception("刪除準備語句失敗: " . $conn->error);
    }
    $stmtDelete->bind_param("i", $userId);
    if (!$stmtDelete->execute()) {
        throw new Exception("刪除原有資料失敗: " . $stmtDelete->error);
    }

    // 確認刪除
    if ($stmtDelete->affected_rows === 0) {
        throw new Exception("沒有找到需要刪除的資料");
    }
    $stmtDelete->close(); // 關閉刪除語句

    // 逐條處理學校和科系資料
    $sqlGetSchoolId = "SELECT id FROM Secondskill WHERE name = ? LIMIT 1";
    $sqlGetDepartmentId = "SELECT department_id FROM School_Department WHERE department_name = ? LIMIT 1";

    $stmtSchool = $conn->prepare($sqlGetSchoolId);
    $stmtDepartment = $conn->prepare($sqlGetDepartmentId);

    // 使用 INSERT ... ON DUPLICATE KEY UPDATE 來處理資料更新
    $sqlInsert = 'INSERT INTO preferences (student_user, preference_rank, Secondskill_id, department_id)
        VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        Secondskill_id = VALUES(Secondskill_id),
        department_id = VALUES(department_id),
        preference_rank = VALUES(preference_rank)';

    $stmtInsert = $conn->prepare($sqlInsert);

    foreach ($data['preferences'] as $preference) {
        $preference_rank = $preference['preference_rank'];
        $school_name = $preference['school_name'];
        $department_name = $preference['department_name'];

        // 查詢學校 ID
        $stmtSchool->bind_param("s", $school_name);
        $stmtSchool->execute();
        $stmtSchool->bind_result($Secondskill_id);
        $stmtSchool->fetch();
        $stmtSchool->free_result(); // 釋放結果集

        // 查詢科系 ID
        $stmtDepartment->bind_param("s", $department_name);
        $stmtDepartment->execute();
        $stmtDepartment->bind_result($department_id);
        $stmtDepartment->fetch();
        $stmtDepartment->free_result(); // 釋放結果集

        // 綁定參數並執行插入或更新
        $stmtInsert->bind_param("iiii", $userId, $preference_rank, $Secondskill_id, $department_id);
        if (!$stmtInsert->execute()) {
            throw new Exception("插入資料失敗: " . $stmtInsert->error);
        }
    }

    // 提交交易
    $conn->commit();

    echo json_encode(['success' => true, 'message' => '變更已成功保存']);
} catch (Exception $e) {
    // 發生錯誤時回滾
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => '操作失敗: ' . $e->getMessage()]);
}

// 關閉連線
$stmtSchool->close();
$stmtDepartment->close();
$stmtInsert->close();
$conn->close();
?>