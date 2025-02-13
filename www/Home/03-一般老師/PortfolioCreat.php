<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // 取得檔案資訊
        $fileName = basename($_FILES['file']['name']);
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        $fileSize = $_FILES['file']['size'];

        // 允許的檔案類型
        $allowedTypes = ['image/png', 'image/jpeg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "<script>alert('檔案類型不支援！僅支援 PNG, JPEG, DOC 和 DOCX 格式。'); window.location.href='Portfolio1.php';</script>";
            exit;
        }

        // 連接資料庫
        $servername = "127.0.0.1";
        $username = "HCHJ";
        $password = "xx435kKHq";
        $dbname = "HCHJ";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("資料庫連線失敗：" . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");

        // 檢查資料庫是否已有相同檔名
        $checkSql = "SELECT COUNT(*) FROM portfolio WHERE file_name = ?";
        $stmt = $conn->prepare($checkSql);
        $stmt->bind_param("s", $fileName);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo "<script>alert('檔案名稱已存在，請重新命名或選擇其他檔案！'); window.location.href='Portfolio1.php';</script>";
            exit;
        }

        // 取得表單資料
        $studentId = intval($_POST['student_id']);
        $category = $conn->real_escape_string($_POST['category']);
        $organization = ($category === "相關證照") ? $conn->real_escape_string($_POST['sub_category']) : null;
        $certificateName = isset($_POST['certificate_name']) ? $conn->real_escape_string($_POST['certificate_name']) : null;

        // 若為圖片，壓縮後再儲存
        if ($fileType === 'image/jpeg' || $fileType === 'image/png') {
            list($origWidth, $origHeight) = getimagesize($fileTmpPath);
            $targetWidth = 200;
            $targetHeight = 200;

            $sourceImage = ($fileType === 'image/jpeg') ? imagecreatefromjpeg($fileTmpPath) : imagecreatefrompng($fileTmpPath);
            $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $origWidth, $origHeight);

            $resizedTmpPath = tempnam(sys_get_temp_dir(), 'resized_');
            ($fileType === 'image/jpeg') ? imagejpeg($resizedImage, $resizedTmpPath, 90) : imagepng($resizedImage, $resizedTmpPath);

            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            $fileContent = file_get_contents($resizedTmpPath);
            unlink($resizedTmpPath);
        } else {
            $fileContent = file_get_contents($fileTmpPath);
        }

        // 儲存到資料庫
        $sql = "INSERT INTO portfolio (student_id, category, organization, certificate_name, file_name, file_content, upload_time) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL 錯誤：" . $conn->error);
        }
        $stmt->bind_param("isssss", $studentId, $category, $organization, $certificateName, $fileName, $fileContent);

        if ($stmt->execute()) {
            echo "<script>alert('檔案上傳成功！'); window.location.href='Portfolio1.php';</script>";
        } else {
            echo "<script>alert('資料儲存失敗：" . $stmt->error . "'); window.location.href='Portfolio1.php';</script>";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "<script>alert('檔案上傳錯誤！'); window.location.href='Portfolio1.php';</script>";
    }
}
?>
