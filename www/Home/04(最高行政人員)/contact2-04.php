<?php
session_start();
// 資料庫連接參數
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 確保 SESSION 中儲存了唯一識別符 (例如 user_id 或 username)
$userData = $_SESSION['user'];
$userId = $userData['user'];

// 查詢資料庫以確認使用者是否存在
$query = sprintf("SELECT user FROM `user` WHERE user = '%s'", $conn->real_escape_string($userId));
$result = $conn->query($query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        // 讀取檔案內容
        $fileData = file_get_contents($_FILES['image']['tmp_name']);
        $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = array('jpg', 'jpeg', 'png');

        if (in_array($fileExt, $allowed)) {
            // 使用 prepare 和 bind_param 更新圖片欄位
            $sql = "UPDATE `user` SET `image` = ? WHERE `user` = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("資料庫錯誤：無法準備查詢語句 - " . $conn->error);
            }

            // 綁定參數，"b" 表示二進位資料，"s" 表示字串
            $stmt->bind_param("bs", $fileData, $userId);

            // 傳送二進位資料
            $stmt->send_long_data(0, $fileData);

            // 執行 SQL 查詢並確認成功
            if ($stmt->execute()) {
               echo" <script>
                  alert('圖片上傳並儲存成功！');
                  window.location.href = '/~HCHJ/Home/contact-04.php';
                </script>";
      
            } else {
                echo" <script>
                  alert('資料庫更新錯誤：');
                  window.location.href = '/~HCHJ/Home/contact-04.php';
                </script>". $stmt->error;
 
            }

            $stmt->close();
        } else {
            echo" <script>
                  alert('不支援的檔案格式！請選擇 JPG, JPEG, 或 PNG 格式的檔案。');
                  window.location.href = '/~HCHJ/Home/contact-04.php';
                </script>";
        }
    } else {
        echo" <script>
        alert('檔案上傳錯誤，錯誤代碼：" . $_FILES['image']['error']."');
        window.location.href = '/~HCHJ/Home/contact-04.php';
      </script>";
    }
} else {
    echo" <script>
    alert('無法接收到檔案');
    window.location.href = '/~HCHJ/Home/contact-04.php';
  </script>";

}

$conn->close();
?>