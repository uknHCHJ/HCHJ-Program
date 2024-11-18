<?php
$servername = "127.0.0.1"; // 伺服器IP或localhost
$username = "HCHJ"; // 資料庫登入帳號
$password = "xx435kKHq"; // 資料庫密碼
$dbname = "HCHJ"; // 資料庫名稱

// 建立與資料庫的連線
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 抓取學校 ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT 	image_path FROM School WHERE school_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($image);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($image) {
    // 設定圖片標頭並輸出
    header("Content-Type: image/jpeg");
    echo $image;
} else {
    // 如果圖片不存在，顯示預設圖片或返回錯誤
    header("Content-Type: image/jpeg");
    readfile("path_to_default_image.jpg");
}
?>