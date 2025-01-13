<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 接收刪除請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        // 建立連線
        $conn = new mysqli($servername, $username, $password, $dbname);

        // 確認連線是否成功
        if ($conn->connect_error) {
            die("連線失敗：" . $conn->connect_error);
        }

        // 刪除資料
        $stmt = $conn->prepare("DELETE FROM portfolio WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            echo "資料已成功刪除！";
        } else {
            echo "資料刪除失敗：" . $stmt->error;
        }

        // 關閉連線
        $stmt->close();
        $conn->close();
    } else {
        echo "未收到資料 ID！";
    }
} else {
    echo "無效的請求方式！";
}
?>
