<?php
// 啟動 session 以確認使用者登入狀態
session_start();

// 檢查使用者是否已登入
if (!isset($_SESSION['user'])) {
    http_response_code(403);  // 回傳 403 禁止存取
    echo json_encode(array("error" => "未授權的存取。"));
    exit();
}

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);

if (!$link) {
    http_response_code(500);  // 伺服器錯誤
    echo json_encode(array("error" => "無法連接資料庫。"));
    exit();
}

// 設定資料庫編碼為 UTF-8
mysqli_query($link, 'SET NAMES UTF8');

// 假設使用者的權限 ID 存在於 session 中（登入時已儲存）
$user = $_SESSION['user'];

// 查詢使用者的權限
$sql = "SELECT Permissions FROM user WHERE id = '" . $user['id'] . "'";
$result = mysqli_query($link, $sql);

if ($result && $row = mysqli_fetch_assoc($result)) {
    // 將權限字串分割為陣列
    $permissions_ids = explode(',', $row['Permissions']);
    
    // 回傳 JSON 格式的權限 ID 陣列
    header('Content-Type: application/json');
    echo json_encode($permissions_ids);
} else {
    http_response_code(500);
    echo json_encode(array("error" => "無法取得權限資料。"));
}

// 關閉資料庫連線
mysqli_close($link);
?>
