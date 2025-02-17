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
    // 當類別為「專業證照」時，會有 organization 與 certificate_name
    $organization     = isset($_POST['organization']) ? $_POST['organization'] : '';
    $certificate_name = isset($_POST['certificate_name']) ? $_POST['certificate_name'] : '';

    // 若有 grade 與 class，請從表單取得；若沒有可給預設值（這裡給空字串）
    $grade = isset($_POST['grade']) ? $_POST['grade'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';

    // 取得 force 參數，判斷是否為覆蓋上傳（使用者確認覆蓋）
    $force = isset($_POST['force']) ? $_POST['force'] : 0;

    // 簡單檢查必要欄位
    if (empty($student_id) || empty($category)) {
        echo "缺少必要資料。";
        header("Location: Portfolio1.php");
        exit;
    }

    // 檢查檔案是否上傳成功
    if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        echo "檔案上傳失敗。";
        header("Location: Portfolio1.php");
        exit;
    }

    // 檢查副檔名是否允許
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

    // 讀取上傳檔案的內容（直接存入資料庫，不另存至目錄）
    $fileContent = file_get_contents($fileTmpPath);
    if ($fileContent === false) {
        echo "讀取檔案失敗。";
        header("Location: Portfolio1.php");
        exit;
    }

    // 當上傳的資料類別不是「專業證照」，將 organization 與 certificate_name 設為 NULL
    if ($category !== '專業證照') {
        $organization     = null;
        $certificate_name = null;
    }

    // 建立資料庫連線
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        echo "資料庫連線失敗：" . $conn->connect_error;
        header("Location: Portfolio1.php");
        exit;
    }

    // 檢查是否已經存在相同 student_id, category 與 file_name 的資料
    $sql_check = "SELECT id FROM portfolio WHERE student_id = ? AND category = ? AND file_name = ?";
    $stmt_check = $conn->prepare($sql_check);
    if (!$stmt_check) {
        echo "檢查資料失敗：" . $conn->error;
        header("Location: Portfolio1.php");
        exit;
    }
    $stmt_check->bind_param("iss", $student_id, $category, $originalFileName);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        // 已有相同資料
        $row = $result_check->fetch_assoc();
        $existingId = $row['id'];
        if (!$force) {
            // 未確認覆蓋，回傳錯誤訊息讓前端跳出小視窗要求確認
            echo "duplicate";
            header("Location: Portfolio1.php?error=duplicate");
            exit;
        } else {
            // 已確認覆蓋，執行更新：更新現有記錄的內容
            $sql_update = "UPDATE portfolio 
                           SET grade = ?, class = ?, organization = ?, certificate_name = ?, file_content = ?, upload_time = NOW() 
                           WHERE id = ?";
            $stmt_update = $conn->prepare($sql_update);
            if (!$stmt_update) {
                echo "更新資料準備失敗: " . $conn->error;
                header("Location: Portfolio1.php");
                exit;
            }
            // 為 blob 欄位設定一個 NULL 佔位符，稍後用 send_long_data 傳送檔案內容
            $null = NULL;
            // 格式說明："ssssbi"：四個字串、1 blob、1 整數
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
        // 無重複資料，執行新增
        $sql_insert = "INSERT INTO portfolio (grade, class, student_id, category, organization, certificate_name, file_name, file_content, upload_time)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt_insert = $conn->prepare($sql_insert);
        if (!$stmt_insert) {
            echo "資料庫準備失敗: " . $conn->error;
            header("Location: Portfolio1.php");
            exit;
        }
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
