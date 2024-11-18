<?php
// 資料庫連接設定
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);

// 檢查連接是否成功
if (!$link) {
    // 輸出錯誤訊息並以 JSON 格式回應
    $response[0] = "無法連接資料庫：" . mysqli_connect_error();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 設置字符集
mysqli_query($link, 'SET NAMES UTF8');

// 設定回應內容類型為 JSON
header('Content-Type: application/json');

// 檢查是否有傳遞 user 參數，並且不為空
session_start();
if (!isset($_SESSION['user'])) {
    $response[0] = "未登入";
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit();
}

$userId = $_SESSION['user']['user']; // 從 SESSION 中獲取 user_id

// 準備查詢語句，查詢特定使用者的競賽資料
$query = "SELECT name FROM history WHERE user_id = '$userId'";
$result = mysqli_query($link, $query);

// 檢查查詢是否成功
if (!$result) {
    // 回傳查詢失敗的錯誤訊息
    $response[0] = "查詢失敗：" . mysqli_error($link);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 構建競賽資料陣列
$competitions = array();
while ($row = mysqli_fetch_assoc($result)) {
    $competitions[] = $row;
}

// 如果查詢結果為空，返回提示訊息
if (empty($competitions)) {
    $response[0] = "查無資料";
} else {
    // 將競賽資料存入回應陣列
    $response = $competitions; // 使用索引式陣列
}

// 回傳 JSON 格式的資料
echo json_encode($response, JSON_UNESCAPED_UNICODE);

// 釋放結果集並關閉資料庫連接
mysqli_free_result($result);
mysqli_close($link);

// 開啟錯誤報告（僅在開發過程中使用）
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
