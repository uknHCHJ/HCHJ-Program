<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // 取得檔案資訊
        $fileName = basename($_FILES['file']['name']); // 原始檔案名稱
        $fileTmpPath = $_FILES['file']['tmp_name'];    // 檔案臨時路徑
        $fileType = mime_content_type($fileTmpPath);   // 檔案 MIME 類型
        $fileSize = $_FILES['file']['size'];           // 檔案大小

        // 支援的檔案類型
        $allowedTypes = [
            'image/png', 
            'image/jpeg', 
            'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];
        if (!in_array($fileType, $allowedTypes)) {
            die("檔案類型不支援！僅支援 PNG, JPEG, DOC 和 DOCX 格式。");
            header("Location:Portfolio1.php");
        }

        // 資料庫連線設定
        $servername = "127.0.0.1"; // 資料庫伺服器地址
        $username = "HCHJ";       // 使用者名稱
        $password = "xx435kKHq";  // 密碼
        $dbname = "HCHJ";         // 資料庫名稱

        // 建立資料庫連線
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("資料庫連線失敗：" . $conn->connect_error);
        }

        // 設定資料庫連線的編碼
        $conn->set_charset("utf8mb4");

        // 取得學生 ID 和類別
        $studentId = intval($_POST['student_id']); // 學生ID
        $category = $conn->real_escape_string($_POST['category']); // 類別名稱
        $subCategory = isset($_POST['sub_category']) ? $conn->real_escape_string($_POST['sub_category']) : null; // 相關證照機構（如果有）

        // 如果是圖片，調整圖片大小
        if ($fileType === 'image/jpeg' || $fileType === 'image/png') {
            list($origWidth, $origHeight) = getimagesize($fileTmpPath); // 原始寬高
            $targetWidth = 200;  // 目標寬度
            $targetHeight = 200; // 目標高度

            // 建立圖片資源
            $sourceImage = null;
            if ($fileType === 'image/jpeg') {
                $sourceImage = imagecreatefromjpeg($fileTmpPath);
            } elseif ($fileType === 'image/png') {
                $sourceImage = imagecreatefrompng($fileTmpPath);
            }

            if ($sourceImage === null) {
                die("無法處理圖片檔案！");
                header("Location:Portfolio1.php");
            }

            // 建立新圖片資源
            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);

            // 調整大小
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

            // 儲存調整後的圖片到臨時檔案
            $resizedTmpPath = tempnam(sys_get_temp_dir(), 'resized_'); // 建立臨時檔案路徑
            if ($fileType === 'image/jpeg') {
                imagejpeg($resizedImage, $resizedTmpPath, 90); // 儲存為 JPEG
            } elseif ($fileType === 'image/png') {
                imagepng($resizedImage, $resizedTmpPath);      // 儲存為 PNG
            }

            // 清理記憶體
            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            // 讀取調整後的圖片內容
            $fileContent = file_get_contents($resizedTmpPath);

            // 刪除臨時檔案
            unlink($resizedTmpPath);
        } 
        // 如果是 Word 檔案，直接讀取內容
        elseif ($fileType === 'application/msword' || $fileType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $fileContent = file_get_contents($fileTmpPath);
        } else {
            die("未知的檔案類型！");
            header("Location:Portfolio1.php");
        }

        // 儲存到資料庫
        $sql = "INSERT INTO portfolio (student_id, category, organization, file_name, file_content, upload_time) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("預處理語句建立失敗：" . $conn->error);
            header("Location:Portfolio1.php");
        }
        $stmt->bind_param("issss", $studentId, $category, $subCategory, $fileName, $fileContent);

        if ($stmt->execute()) {
            echo "檔案上傳並儲存成功！";
            header("Location:Portfolio1.php");
        } else {
            echo "資料儲存失敗：" . $stmt->error;
            header("Location:Portfolio1.php");
        }

        // 關閉資料庫連線與預處理語句
        $stmt->close();
        $conn->close();
    } else {
        die("檔案上傳錯誤！");
        header("Location:Portfolio1.php");
    }
}
?>
