<?php
session_start();

// 驗證是否登入
if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "未登入"]);
    exit();
}

// 獲取使用者資訊
$userData = $_SESSION['user'];
$userId = $userData['user'];

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $dbUser, $dbPassword, $dbname);

// 檢查連線是否成功
if (!$link) {
    echo json_encode(["success" => false, "message" => "無法連接資料庫：" . mysqli_connect_error()]);
    exit();
}

// 設置字符集
mysqli_query($link, 'SET NAMES UTF8');

// 設定回應內容類型為 JSON
header('Content-Type: application/json');

// 獲取前端傳來的 JSON 資料
$data = json_decode(file_get_contents("php://input"), true);

// 確保請求中包含必要參數
if (!isset($data['preference_rank']) || !isset($data['department_name']) || !isset($data['school_name'])) {
    echo json_encode(["success" => false, "message" => "缺少必要參數"]);
    exit();
}


// 避免 SQL 注入
$school_name = mysqli_real_escape_string($link, $data['school_name']);
$department_name = mysqli_real_escape_string($link, $data['department_name']);
$preference_rank = (int)$data['preference_rank'];

// 檢查是否存在該筆資料
$checkQuery = "SELECT * FROM Preferences 
               WHERE student_user = '$userId' 
               AND school_name = '$school_name' 
               AND department_name = '$department_name' 
               AND preference_rank = '$preference_rank'";
$checkResult = mysqli_query($link, $checkQuery);

if (mysqli_num_rows($checkResult) == 0) {
    echo json_encode(["success" => false, "message" => "資料不存在"]);
    exit();
}

// 執行刪除動作
$deleteQuery = "DELETE FROM Preferences 
                WHERE student_user = '$userId' 
                AND school_name = '$school_name' 
                AND department_name = '$department_name' 
                AND preference_rank = '$preference_rank'";

$deleteResult = mysqli_query($link, $deleteQuery);

if ($deleteResult && mysqli_affected_rows($link) > 0) {
    // 重新調整 preference_rank，確保志願序號連續不間斷
    $rankUpdateQuery = "SET @new_rank = 0;
                        UPDATE Preferences 
                        SET preference_rank = (@new_rank := @new_rank + 1) 
                        WHERE student_user = '$userId' 
                        ORDER BY preference_rank ASC";

    mysqli_multi_query($link, $rankUpdateQuery);

    echo json_encode(["success" => true, "message" => "刪除成功"]);
} else {
    echo json_encode(["success" => false, "message" => "刪除失敗"]);
}

// 釋放資源並關閉連線
mysqli_close($link);
?>
