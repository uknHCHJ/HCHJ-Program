<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

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
    $conn->set_charset("utf8mb4");

    // 獲取 POST 資料
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $studentId = intval($_POST['student_id']);
    $category = $conn->real_escape_string($_POST['category']);
    $organization = $conn->real_escape_string($_POST['organization']);
    $certificateName = $conn->real_escape_string($_POST['certificate_name']);
    $grade = $conn->real_escape_string($_POST['grade']);
    $class = $conn->real_escape_string($_POST['class']);

    // 檢查是否已有相同的 category 和 file_name，但不同 student_id 允許
    $checkSql = "SELECT COUNT(*) FROM portfolio WHERE category = ? AND file_name = ? AND student_id != ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ssi", $category, $_FILES['file']['name'], $studentId);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "已有相同類別與檔名的資料，請重新確認！";
        exit;
    }

    // 處理文件上傳
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file']['name']);
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);
        $fileSize = $_FILES['file']['size'];

        // 允許的檔案類型
        $allowedTypes = ['image/png', 'image/jpeg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        if (!in_array($fileType, $allowedTypes)) {
            echo "檔案類型不支援！";
            exit;
        }

        // 若為圖片，依勞動部證照標準調整尺寸
        if ($fileType === 'image/jpeg' || $fileType === 'image/png') {
            list($origWidth, $origHeight) = getimagesize($fileTmpPath);
            
            // 勞動部標準尺寸 (寬: 高 = 297:210)
            $targetWidth = 297;
            $targetHeight = 210;

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

        // **如果 id 存在，表示是更新資料**
        if ($id) {
            // 更新操作
            $sql = "UPDATE portfolio SET grade = ?, class = ?, category = ?, organization = ?, certificate_name = ?, file_name = ?, file_size = ?, file_content = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssisi", $grade, $class, $category, $organization, $certificateName, $fileName, $fileSize, $fileContent, $id);
            $stmt->execute();
            $stmt->close();
        } else {
            // 新增操作
            $sql = "INSERT INTO portfolio (student_id, grade, class, category, organization, certificate_name, file_name, file_size, file_content, upload_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("isssssssi", $studentId, $grade, $class, $category, $organization, $certificateName, $fileName, $fileSize, $fileContent);
            $stmt->execute();
            $stmt->close();
        }
    }

    // 儲存專業證照分類到資料庫
    $categorySql = "INSERT INTO certificate_categories (student_id, category) VALUES (?, ?) ON DUPLICATE KEY UPDATE category = VALUES(category)";
    $stmt = $conn->prepare($categorySql);
    $stmt->bind_param("is", $studentId, $category);
    $stmt->execute();
    $stmt->close();

    echo "操作成功！";
    header("Location:Portfolio1.php");
    $conn->close();
}
?>
