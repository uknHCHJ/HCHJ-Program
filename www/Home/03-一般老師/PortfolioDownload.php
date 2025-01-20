<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 接收檔案 ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // 建立連線
    $conn = new mysqli($servername, $username, $password, $dbname);

    // 確認連線是否成功
    if ($conn->connect_error) {
        die("連線失敗：" . $conn->connect_error);
    }

    // 查詢檔案資料
    $stmt = $conn->prepare("SELECT file_name, file_content FROM portfolio WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($fileName, $fileContent);

    if ($stmt->fetch()) {
        // 設定檔案下載標頭
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Content-Type: application/octet-stream");
        echo $fileContent;
    } else {
        echo "找不到檔案！";
        header("Location:Portfolio1.php");
    }

    // 關閉連線
    $stmt->close();
    $conn->close();
} else {
    echo "無效的請求！";
    header("Location:Portfolio1.php");
}
?>
