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

    // 簡單檢查必要欄位
    if (empty($student_id) || empty($category)) {
        die("缺少必要資料。");
    }

    // 檢查檔案是否上傳成功
    if (!isset($_FILES['file']) || $_FILES['file']['error'] != UPLOAD_ERR_OK) {
        die("檔案上傳失敗。");
    }

    // 檢查副檔名是否允許
    $allowedExtensions = ['png', 'jpg', 'jpeg', 'doc', 'docx'];
    $file = $_FILES['file'];
    $originalFileName = $file['name'];
    $fileTmpPath      = $file['tmp_name'];
    $fileExtension    = strtolower(pathinfo($originalFileName, PATHINFO_EXTENSION));

    if (!in_array($fileExtension, $allowedExtensions)) {
        die("只允許上傳 PNG, JPG, DOC, DOCX 檔案。");
    }

    // 讀取上傳檔案的內容（直接存入資料庫，不另存至目錄）
    $fileContent = file_get_contents($fileTmpPath);
    if ($fileContent === false) {
        die("讀取檔案失敗。");
    }

    // 當上傳的資料類別不是「專業證照」，將 organization 與 certificate_name 設為 NULL
    if ($category !== '專業證照') {
        $organization     = null;
        $certificate_name = null;
    }

    // 建立資料庫連線
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("資料庫連線失敗：" . $conn->connect_error);
    }

    /*
      資料表 portfolio 欄位說明：
        - id             : 自動編號 (建議設定 AUTO_INCREMENT)
        - grade          : 年級 (必填)
        - class          : 班級 (必填)
        - student_id     : 學生id
        - category       : 分類
        - file_name      : 檔案名稱（原始檔名）
        - file_content   : 檔案內容 (BLOB)
        - upload_time    : 上傳時間 (自動記錄)
        - organization   : 證照所屬機構 (僅專業證照使用)
        - certificate_name: 證照名稱 (僅專業證照使用)
    */

    // 準備 SQL 指令，注意欄位名稱必須與資料表一致
    $sql = "INSERT INTO portfolio (grade, class, student_id, category, organization, certificate_name, file_name, file_content, upload_time)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("資料庫準備失敗: " . $conn->error);
    }

    // 設定 blob 欄位用的 NULL 佔位符
    $null = NULL;
    // 綁定參數：grade (s), class (s), student_id (i), category (s), organization (s), certificate_name (s), file_name (s), file_content (b)
    $stmt->bind_param("ssissssb", $grade, $class, $student_id, $category, $organization, $certificate_name, $originalFileName, $null);

    // 使用 send_long_data() 傳送 blob 資料 (參數索引從 0 開始：索引 7 對應 file_content)
    $stmt->send_long_data(7, $fileContent);

    if ($stmt->execute()) {
        echo "上傳成功！";
    } else {
        echo "上傳失敗：" . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
