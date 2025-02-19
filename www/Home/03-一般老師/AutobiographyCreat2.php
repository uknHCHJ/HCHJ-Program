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
    $grade            = isset($_POST['grade']) ? $_POST['grade'] : '';
    $class            = isset($_POST['class']) ? $_POST['class'] : '';
    $force            = isset($_POST['force']) ? $_POST['force'] : 0;

    if (empty($student_id) || empty($category)) {
        echo "缺少必要資料。";
        header("Location: Portfolio1.php");
        exit;
    }

    if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        echo "檔案上傳失敗。";
        header("Location: Portfolio1.php");
        exit;
    }

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

    $sql_check = "SELECT id FROM portfolio WHERE student_id = ? AND category = ? AND file_name = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("iss", $student_id, $category, $originalFileName);
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
            $sql_update = "UPDATE portfolio SET grade = ?, class = ?, organization = ?, certificate_name = ?, file_content = ?, upload_time = NOW() WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            $null = NULL;
            $stmt_update->bind_param("ssssbi", $grade, $class, $organization, $certificate_name, $null, $existingId);
            $stmt_update->send_long_data(4, $fileContent);
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
        $sql_insert = "INSERT INTO portfolio (grade, class, student_id, category, organization, certificate_name, file_name, file_content, upload_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        $null = NULL;
        $stmt_insert->bind_param("ssissssb", $grade, $class, $student_id, $category, $organization, $certificate_name, $originalFileName, $null);
        $stmt_insert->send_long_data(7, $fileContent);
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
