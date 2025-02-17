<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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

        // 取得表單資料
        $category = $_POST['category'];
        $organization = ($category === "專業證照") ? $_POST['sub_category'] : null;

        // 設定圖片大小
        $resizeWidth = 200;
        $resizeHeight = 200;
        if ($category === "專業證照") {
            $resizeWidth = 800;  // 勞動部證照標準
            $resizeHeight = 600;
        }

        // 若為圖片，壓縮後再儲存
        if ($fileType === 'image/jpeg' || $fileType === 'image/png') {
            list($origWidth, $origHeight) = getimagesize($fileTmpPath);
            $sourceImage = ($fileType === 'image/jpeg') ? imagecreatefromjpeg($fileTmpPath) : imagecreatefrompng($fileTmpPath);
            $resizedImage = imagecreatetruecolor($resizeWidth, $resizeHeight);
            imagecopyresampled($resizedImage, $sourceImage, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $origWidth, $origHeight);

            $resizedTmpPath = tempnam(sys_get_temp_dir(), 'resized_');
            ($fileType === 'image/jpeg') ? imagejpeg($resizedImage, $resizedTmpPath, 90) : imagepng($resizedImage, $resizedTmpPath);

            imagedestroy($sourceImage);
            imagedestroy($resizedImage);

            $fileContent = file_get_contents($resizedTmpPath);
            unlink($resizedTmpPath);
        } else {
            $fileContent = file_get_contents($fileTmpPath);
        }

        // 連接資料庫
        $conn = new mysqli("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
        if ($conn->connect_error) {
            die("資料庫連線失敗：" . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");

        // 儲存到資料庫
        $sql = "INSERT INTO portfolio (category, organization, file_name, file_content, upload_time) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("SQL 錯誤：" . $conn->error);
        }
        $stmt->bind_param("ssss", $category, $organization, $fileName, $fileContent);

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
