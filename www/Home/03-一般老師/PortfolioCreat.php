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

    // 檢查並處理檔案
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $file_name = $_FILES['file']['name'];
        $file_content = file_get_contents($_FILES['file']['tmp_name']);

        // 插入資料庫
        $sql = "INSERT INTO portfolio (student_id, category, file_name, file_content, upload_time) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $student_id, $category, $file_name, $file_content);

        if ($stmt->execute()) {
            echo "檔案上傳成功！";
            header("Location:Portfolio1.php");
        } else {
            echo "資料儲存失敗：" . $stmt->error;
            header("Location:Portfolio1.php");
        }

        $stmt->close();
    } else {
        echo "檔案上傳錯誤！";
    }

    $conn->close();
}
?>
