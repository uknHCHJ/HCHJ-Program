<?php
// 開啟 session
session_start();

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 接收檔案上傳請求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['student_id']) && isset($_POST['category']) && isset($_FILES['file'])) {
        $student_id = $_POST['student_id'];
        $category = $_POST['category'];
        $file = $_FILES['file'];

        // 檢查檔案是否上傳成功
        if ($file['error'] === UPLOAD_ERR_OK) {
            $fileName = $file['name'];
            $tmpName = $file['tmp_name'];

            // 讀取檔案內容
            $fileContent = file_get_contents($tmpName);

            // 建立連線
            $conn = new mysqli($servername, $username, $password, $dbname);

            // 確認連線是否成功
            if ($conn->connect_error) {
                die("連線失敗：" . $conn->connect_error);
            }

            // 插入資料到資料庫
            $stmt = $conn->prepare("INSERT INTO portfolio (student_id, category, file_name, file_content, upload_time) VALUES (?, ?, ?, ?, NOW())");
            $stmt->bind_param("isss", $student_id, $category, $fileName, $fileContent);

            if ($stmt->execute()) {
                echo "資料已成功上傳！";
                header("Location:Portfolio1.php");
            } else {
                echo "資料上傳失敗：" . $stmt->error;
            }

            // 關閉連線
            $stmt->close();
            $conn->close();
        } else {
            echo "檔案上傳失敗，錯誤碼：" . $file['error'];
        }
    } else {
        echo "未收到必要的表單資料，請確認輸入！";
    }
} else {
    echo "無效的請求方式！";
}
?>
