<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

// 設定資料庫連線的編碼
$conn->set_charset("utf8mb4");

// 根據 ID 取得圖片內容
$fileId = intval($_GET['id']); // 從 URL 中傳遞的 ID
$sql = "SELECT file_name, file_content FROM portfolio WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $fileId);
$stmt->execute();
$stmt->bind_result($fileName, $fileContent);

if ($stmt->fetch()) {
    // 設定正確的 Content-Type 顯示圖片
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $mimeType = ($fileExtension === 'jpg' || $fileExtension === 'jpeg') ? 'image/jpeg' : 'image/png';
    header("Content-Type: $mimeType");
    echo $fileContent;
} else {
    echo "找不到圖片！";
    header("Location:Portfolio1.php");
}

$stmt->close();
$conn->close();
?>
