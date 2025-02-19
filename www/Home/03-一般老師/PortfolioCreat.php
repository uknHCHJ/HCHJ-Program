<?php 
// PortfolioCreat.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 資料庫連線設定
    $servername = "127.0.0.1";
    $username   = "HCHJ";
    $password   = "xx435kKHq";
    $dbname     = "HCHJ";

    // 從前端取得資料
    $student_id       = isset($_POST['student_id']) ? $_POST['student_id'] : '';
    $category         = isset($_POST['category']) ? $_POST['category'] : '';
    $organization     = isset($_POST['organization']) ? $_POST['organization'] : '';
    $certificate_name = isset($_POST['certificate_name']) ? $_POST['certificate_name'] : '';
    $file_name        = isset($_POST['file_name']) ? $_POST['file_name'] : '';
    $autobiography_content = isset($_POST['autobiography_content']) ? $_POST['autobiography_content'] : '';
    $force            = isset($_POST['force']) ? $_POST['force'] : 0;

    if (empty($student_id) || empty($category)) {
        echo "缺少必要資料。";
        header("Location: Portfolio1.php");
        exit;
    }

    if ($category === '自傳' && empty($autobiography_content)) {
        echo "自傳內容必須提供。";
        header("Location: Portfolio1.php");
        exit;
    }

    // 處理檔案上傳
    if ($category !== '自傳' && (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK)) {
        echo "檔案上傳失敗。";
        header("Location: Portfolio1.php");
        exit;
    }

    // 檢查上傳的檔案類型
    if ($category !== '自傳') {
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'doc', 'docx'];
        $file = $_FILES['file'];
        $originalFileName = $file['name'];
        $fileTmpPath      = $file['tmp_name'];
        $fileExtension    = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedExtensions)) {
            echo "只允許上傳 PNG, JPG, DOC, DOCX 檔案。";
            header("Location: Portfolio1.php");
            exit;
        }

        $fileContent = file_get_contents($fileTmpPath);
        if ($fileContent === false) {
            echo "讀取檔案失敗。";
            header("Location: Portfolio1.php");
            exit;
        }
    } else {
        $fileContent = null; // 自傳不需要檔案內容
    }

    // 如果不是自傳類別，清空證照相關欄位
    if ($category !== '專業證照') {
        $organization     = null;
        $certificate_name = null;
    }

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "資料庫連線失敗：" . $conn->connect_error;
        header("Location: Portfolio1.php");
        exit;
    }

    // 檢查是否已經有相同資料
    $sql_check = "SELECT id FROM portfolio WHERE student_id = ? AND category = ? AND file_name = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("iss", $student_id, $category, $file_name);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        $existingId = $row['id'];
        if (!$force) {
            echo "duplicate";
            header("Location: Portfolio1.php?error=duplicate");
            exit;
        } else {
            // 更新資料
            if ($category === '自傳') {
                $sql_update = "UPDATE portfolio SET file_name = ?, autobiography_content = ?, upload_time = NOW() WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ssi", $file_name, $autobiography_content, $existingId);
            } else {
                $sql_update = "UPDATE portfolio SET organization = ?, certificate_name = ?, file_content = ?, upload_time = NOW() WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ssbi", $organization, $certificate_name, $fileContent, $existingId);
                $stmt_update->send_long_data(3, $fileContent);
            }

            if ($stmt_update->execute()) {
                echo "上傳成功，已更新現有資料！";
                header("Location: Portfolio1.php");
                exit;
            } else {
                echo "更新資料失敗：" . $stmt_update->error;
                header("Location: Portfolio1.php");
                exit;
            }
            $stmt_update->close();
        }
    } else {
        // 插入新資料
        // 插入新資料
        if ($category === '自傳') {
            $sql_insert = "INSERT INTO portfolio (student_id, category, file_name, autobiography_content, upload_time) 
                        VALUES (?, ?, ?, ?, NOW())";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("issss", $student_id, $category, $file_name, $autobiography_content);
        } else {
            $sql_insert = "INSERT INTO portfolio (student_id, category, organization, certificate_name, file_name, file_content, upload_time) 
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt_insert = $conn->prepare($sql_insert);
            $null = NULL;
            $stmt_insert->bind_param("issssssb", $student_id, $category, $organization, $certificate_name, $file_name, $null);
            $stmt_insert->send_long_data(6, $fileContent);
        }

        if ($stmt_insert->execute()) {
            echo "上傳成功！";
            header("Location: Portfolio1.php");
            exit;
        } else {
            echo "上傳失敗：" . $stmt_insert->error;
            header("Location: Portfolio1.php");
            exit;
        }
        $stmt_insert->close();
            }

    $stmt_check->close();
    $conn->close();
}
?>
