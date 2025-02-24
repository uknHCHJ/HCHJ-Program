<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 確認連線是否成功
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 確保請求中有 `id` 參數
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("無效的請求");
}

$id = intval($_GET['id']); // 防止 SQL 注入

// 查詢檔案資料
$sql = "SELECT file_name, file_content FROM portfolio WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();

// 檢查是否找到檔案
if ($stmt->num_rows === 0) {
    die("找不到檔案");
}

$stmt->bind_result($file_name, $file_content);
$stmt->fetch();

// 設定 HTTP 標頭
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=" . basename($file_name));
header("Content-Length: " . strlen($file_content));
header("Pragma: public");

// 輸出檔案內容
echo $file_content;

// 關閉連線
$stmt->close();
$conn->close();
exit;
?>
