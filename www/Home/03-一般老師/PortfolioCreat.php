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

    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $studentId = intval($_POST['student_id']);
    $organization = $conn->real_escape_string($_POST['organization']);
    $certificateName = $conn->real_escape_string($_POST['certificate_name']);

    // 檢查同一 organization 下是否有相同名稱的資料
    $checkSql = "SELECT COUNT(*) FROM portfolio WHERE student_id = ? AND organization = ? AND certificate_name = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("iss", $studentId, $organization, $certificateName);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "已有相同證書名稱的資料，請重新確認！";
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

        // 若為圖片，壓縮後儲存
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
        $sql = "INSERT INTO portfolio (student_id, category, organization, certificate_name, file_name, file_content, upload_time) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssss", $studentId, $_POST['category'], $organization, $certificateName, $fileName, $fileContent);
        $stmt->execute();
        $stmt->close();
    }

    // 刪除資料庫記錄
    if ($id) {
        $sql = "DELETE FROM portfolio WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }

    // 發送通知郵件
    function sendEmailToTeacher($studentName, $conn) {
        $sql = "SELECT email FROM testemail WHERE FIND_IN_SET('2', Permissions)";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            $teacheremail = $row['email'];
            if (!empty($teacheremail)) {
                mail($teacheremail, "學生 $studentName 已上傳證書", "學生 $studentName 上傳了一份新證書", "From: notify@example.com\r\nContent-Type: text/html; charset=UTF-8");
            }
        }
    }
    sendEmailToTeacher($_POST['student_name'], $conn);

    echo "操作成功！";
    header("Location:Portfolio1.php");
    $conn->close();
}
?>
