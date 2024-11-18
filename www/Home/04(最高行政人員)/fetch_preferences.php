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

if (!isset($_SESSION['user'])) {
    $response[0] = "未登入";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

$userId = isset($_GET['user']) ? $_GET['user'] : $userId;

// 查詢使用者競賽偏好
$query = "SELECT user, school_id, department_id, serial_number FROM Preferences WHERE user = '$userId' ORDER BY serial_number ASC";
$result = mysqli_query($link, $query);

if (!$result) {
    $response[0] = "查詢失敗：" . mysqli_error($link);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

$competitions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $schoolId = $row['school_id'];
    $departmentId = $row['department_id'];

    // 使用 school_id 查詢學校名稱
    $schoolQuery = "SELECT school_name FROM School WHERE school_id = '$schoolId'";
    $schoolResult = mysqli_query($link, $schoolQuery);
    $schoolName = mysqli_fetch_assoc($schoolResult)['school_name'];

    // 使用 department_id 查詢系所名稱
    $departmentQuery = "SELECT department_name FROM Department WHERE department_id = '$departmentId'";
    $departmentResult = mysqli_query($link, $departmentQuery);
    $departmentName = mysqli_fetch_assoc($departmentResult)['department_name'];

    // 合併資料
    $row['school_name'] = $schoolName;
    $row['department_name'] = $departmentName;
    
    $competitions[] = $row;

    // 釋放子查詢結果集
    mysqli_free_result($schoolResult);
    mysqli_free_result($departmentResult);
}

if (empty($competitions)) {
    $response[0] = "查無資料";
} else {
    $response = $competitions;
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 釋放主要查詢結果集並關閉連接
mysqli_free_result($result);
mysqli_close($link);

// 開啟錯誤報告
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
