<?php
session_start();

// 資料庫連線設定
$servername = "127.0.0.1"; // 伺服器 IP 或 localhost
$username   = "HCHJ";     // 資料庫帳號
$password   = "xx435kKHq"; // 密碼
$dbname     = "HCHJ";     // 資料庫名稱

// 確保 SESSION 中有儲存唯一識別使用者的資訊
if (!isset($_SESSION['user'])) {
    die(json_encode(["error" => "使用者未登入"]));
}

$userData = $_SESSION['user'];
$userId   = $userData['user'];

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 確認連線成功
if ($conn->connect_error) {
    die(json_encode(["error" => "連線失敗: " . $conn->connect_error]));
}

// **從 `test` 表抓取學校資訊，確保不重複**
$sql = "SELECT DISTINCT school_id, school_name FROM test ORDER BY school_name ASC";
$result = $conn->query($sql);

$schools = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
}

// 返回 JSON 給前端
echo json_encode($schools);

// 關閉連線
$conn->close();
?>
