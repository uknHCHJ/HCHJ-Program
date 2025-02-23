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

$query = "SELECT DISTINCT
            school_name AS school_name, 
            department_name AS department_name,
            preference_rank AS serial_number,
            created_at AS time,
            (
                SELECT COUNT(*) 
                FROM Preferences p2 
                WHERE p2.school_name = p.school_name 
                AND p2.department_name = p.department_name
            ) AS student_count
        FROM Preferences p
        WHERE student_user = '$userId'
        AND (student_user, created_at) IN (
            SELECT student_user, MAX(created_at) 
            FROM Preferences 
            WHERE student_user = '$userId'
            GROUP BY student_user
        )
        ORDER BY preference_rank ASC";

$result = mysqli_query($link, $query);

if (!$result) {
    $response[0] = "查詢失敗：" . mysqli_error($link);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$competitions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $key = $row['serial_number'] . '-' . $row['school_name'] . '-' . $row['department_name'];
    if (!isset($competitions[$key])) {
        $competitions[$key] = $row;
    }
}

$response = array_values($competitions); // 去重後的資料

if (empty($response)) {
    $response[0] = "查無資料";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 釋放結果集並關閉連接
mysqli_free_result($result);
mysqli_close($link);

// 開啟錯誤報告
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>