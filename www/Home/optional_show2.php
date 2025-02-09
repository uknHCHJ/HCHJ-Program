<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $dbUser, $dbPassword, $dbname);

// 檢查連接是否成功
if (!$link) {
    $response[0] = "無法連接資料庫：" . mysqli_connect_error();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 設置字符集
mysqli_query($link, 'SET NAMES UTF8');

// 設定回應內容類型為 JSON
header('Content-Type: application/json');

// 驗證是否登入
if (!isset($_SESSION['user'])) {
    $response[0] = "未登入";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

// 查詢學生最新志願資料（含學校與科系名稱）
$query = "SELECT 
        p.student_user AS user, 
        p.preference_rank AS serial_number, 
        p.Secondskill_id AS school_id, 
        p.department_id AS department_id, 
        p.created_at AS time,
        s.name AS school_name, 
        sd.department_name AS department_name
    FROM preferences p
    LEFT JOIN Secondskill s ON p.Secondskill_id = s.id
    LEFT JOIN School_Department sd ON p.department_id = sd.department_id
    WHERE p.student_user = '$userId'
    AND p.created_at = (
        SELECT MAX(created_at) 
        FROM preferences 
        WHERE student_user = '$userId'
    )
    ORDER BY p.preference_rank ASC";

$result = mysqli_query($link, $query);

if (!$result) {
    $response[0] = "查詢失敗：" . mysqli_error($link);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$competitions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $competitions[] = $row;
}

if (empty($competitions)) {
    $response[0] = "查無資料";
} else {
    $response = $competitions;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 釋放結果集並關閉連接
mysqli_free_result($result);
mysqli_close($link);

// 開啟錯誤報告
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>