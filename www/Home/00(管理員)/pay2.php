<?php
// 資料庫連接設定
$servername = "127.0.0.1";
$username   = "HCHJ";
$password   = "xx435kKHq";
$dbname     = "HCHJ";

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    echo json_encode(["error" => "無法連接資料庫：" . mysqli_connect_error()], JSON_UNESCAPED_UNICODE);
    exit;
}
mysqli_query($link, "SET NAMES UTF8");

// 確保前端有傳送 `class` 參數
if (!isset($_GET['class'])) {
    echo json_encode(["error" => "缺少 class 參數"], JSON_UNESCAPED_UNICODE);
    exit;
}

$class = $_GET['class'];

// 防止 SQL 注入
$class = mysqli_real_escape_string($link, $class);  // ← 這裡原本錯誤

// 查詢學生名單
$sql = "SELECT class, user, grade, name FROM user WHERE class = '$class'";
$result = mysqli_query($link, $sql);

// 確保查詢成功
if (!$result) {
    echo json_encode(["error" => "查詢失敗：" . mysqli_error($link)], JSON_UNESCAPED_UNICODE);
    exit;
}

$students = [];
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

// 關閉資料庫連線
mysqli_close($link);

// 回傳 JSON 格式的學生名單
header('Content-Type: application/json');
echo json_encode($students, JSON_UNESCAPED_UNICODE);
?>
