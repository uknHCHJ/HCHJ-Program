<?php
session_start();
// 資料庫連線
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 確認連線
if (mysqli_connect_errno()) {
    die("連線失敗: " . mysqli_connect_error());
}

$userData = $_SESSION['user']; 

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username = $userData['name'];

// 檢查是否有上傳檔案
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['imageUpload']) && $_FILES['imageUpload']['error'] == UPLOAD_ERR_OK) {
        $upload_date = date('Y-m-d H:i:s');
        $contestname = $_POST['contest-name'];
        // 檔案資訊
        $fileName = $_FILES['imageUpload']['name'];
        $fileType = $_FILES['imageUpload']['type'];
        $fileTmpName = $_FILES['imageUpload']['tmp_name'];

        // 檢查檔案類型
        $allowedTypes = ['image/jpeg'];
        if (in_array($fileType, $allowedTypes)) {

            // 讀檔
            $imageData = file_get_contents($fileTmpName);

            // 將檔案存到資料庫
            $stmt = mysqli_prepare($conn, "INSERT INTO history (img, name, user,upload_date,username) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $imageData, $contestname, $userId, $upload_date, $username);
            //"ssi" = 第一個資料類型為字串s(String)、i為整數(Integer)
            // 儲存檔案
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                        alert('檔案上傳成功!');
                        window.location.href = 'Contest-history(學生).php';
                      </script>";
            } 
            else {
                echo "<script>alert('檔案上傳失敗: " . mysqli_stmt_error($stmt) . "');
                     window.location.href = 'Upload-Contest(學生).php';
                     </script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<script>alert('僅允許上傳 .jpg 檔案格式');
                 window.location.href = 'Upload-Contest(學生).php'; 
                 </script>";
        }
    } else {
        echo "<script>alert('請選擇要上傳的檔案');
             window.location.href = 'Upload-Contest(學生).php'; 
             </script>";
    }
}
mysqli_close($conn);
?>
