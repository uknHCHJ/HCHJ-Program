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
$username = $userData['name'];
// 查詢資料庫以確認使用者是否存在
$query = sprintf("SELECT user FROM `user` WHERE user = '%s'", $conn->real_escape_string($userId));
$result = $conn->query($query);

// 檢查是否成功上傳檔案
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["file"])) {
  // 檢查檔案上傳錯誤代碼
  if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $upload_date = date('Y-m-d H:i:s');
    $fileName = $_FILES['file']['name'];
    $fileType = $_FILES['file']['type'];
    $fileData = file_get_contents($_FILES['file']['tmp_name']); // 讀取檔案內容

    // 準備 SQL 語句並使用 bind_param
    $stmt = $conn->prepare("INSERT INTO file (user,username, name, type, data, upload_date) VALUES (?,?, ?, ?, ?, ?)");
    if ($stmt === false) {
      die("準備 SQL 語句時出現錯誤: " . $conn->error);
    }

    // 使用適當的資料綁定
    $stmt->bind_param("ssssss", $userId, $username, $fileName, $fileType, $fileData, $upload_date);

    // 執行 SQL 語句
    if ($stmt->execute()) {
      echo "<script>
                  alert('檔案上傳成功');
                  window.location.href = '/~HCHJ/Home/recordforreview01-1.php';
                </script>";
    } else {
      echo "<script>
                  alert('檔案上傳失敗: " . $stmt->error . "');
                  window.location.href = '/~HCHJ/Home/upload01-1.php';
                </script>";
    }
    $stmt->close();
  } else { //如果檔案上傳有錯誤，會進行錯誤處理。
    //檢查上傳錯誤代碼，並設置對應的錯誤訊息
    switch ($_FILES['file']['error']) {
      case UPLOAD_ERR_INI_SIZE: //上傳過程中使用的錯誤常數之一。
      case UPLOAD_ERR_FORM_SIZE: //允許上傳的最大檔案大小
        $errorMsg = "檔案超過允許大小"; //檔案大小超過了HTML 表單中 MAX_FILE_SIZE 的限制
        break;
      case UPLOAD_ERR_PARTIAL:
        $errorMsg = "檔案上傳不完整"; //檔案只上傳了一部分，未完整上傳
        break;
      case UPLOAD_ERR_NO_FILE:
        $errorMsg = "未選擇檔案"; //未選擇上傳檔案
        break;
      default:
        $errorMsg = "檔案上傳發生錯誤"; //程式碼有錯誤
    }
    echo "<script>
  alert('$errorMsg');
  window.location.href = '/~HCHJ/Home/recordforreview01-1.php';
</script>";
  }
} else {
  echo "<script>
  alert('無法上傳檔案，請確認表單設定正確');
  window.location.href = '/~HCHJ/Home/recordforreview01-1.php';
</script>";
}

// 關閉資料庫連接
$conn->close();
?>
<script>
  // 函數用來顯示兩次確認的彈出視窗
  function confirmDeletion(form) {
    // 第一層確認
    if (confirm("確定要上傳此檔案嗎？")) {
      // 第二層確認
     
    }
    return false; // 如果取消，則阻止表單提交
  }
</script>