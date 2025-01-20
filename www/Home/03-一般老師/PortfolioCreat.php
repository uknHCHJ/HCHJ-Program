<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 資料庫設定
    $servername = "127.0.0.1";
    $username = "HCHJ";
    $password = "xx435kKHq";
    $dbname = "HCHJ";

    // 建立連線
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("連線失敗：" . $conn->connect_error);
    }

    // 接收表單資料
    $student_id = intval($_POST['student_id']);
    $category = $conn->real_escape_string($_POST['category']);
    $upload_dir = 'uploads/';

    // 檢查並處理檔案
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = basename($_FILES['file']['name']);
        $file_path = $upload_dir . uniqid() . '_' . $file_name;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $file_path)) {
            // 插入資料庫
            $sql = "INSERT INTO portfolio (student_id, category, file_name, file_path, upload_time) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isss", $student_id, $category, $file_name, $file_path);
            if ($stmt->execute()) {
                echo "檔案上傳成功！";
            } else {
                echo "資料儲存失敗：" . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "檔案移動失敗！";
        }
    } else {
        echo "檔案上傳錯誤！";
    }

    $conn->close();
}
?>
