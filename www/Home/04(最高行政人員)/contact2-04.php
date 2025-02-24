<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
session_start();

// 資料庫連接參數
$servername = "127.0.0.1";
$username   = "HCHJ";
$password   = "xx435kKHq";
$dbname     = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("連接失敗: " . $conn->connect_error);
}

// 從 SESSION 取出使用者資訊
$userData      = $_SESSION['user'];
$userId        = $userData['user'];       // 帳號或學號
$studentName   = $userData['name'];
$grade         = $userData['grade'];
$class         = $userData['class'];
$currentUserId = $userData['id'];          // 學生 id
$permissions1  = explode(',', $userData['Permissions']);

// 若有需要取得學生 email（例如用於通知）
$sql = "SELECT * FROM `testemail` WHERE `name`='$studentName'";
$result = mysqli_query($conn, $sql);
if ($result) {
  $studentemail = "";
  while ($row = mysqli_fetch_assoc($result)) {
    $studentemail = $row['email'];
  }
}

// 確認接收請求且有檔案上傳
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {

  if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $fileData = file_get_contents($_FILES['image']['tmp_name']);
      $fileExt  = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $allowed  = ['jpg', 'jpeg', 'png'];

      if (!in_array($fileExt, $allowed)) {
          echo "<script>
                  alert('不支援此檔案格式！');
                  window.location.href = '/~HCHJ/Home/contact1-04.php.php';
                </script>";
          exit;
      }

      // 檢查資料庫中是否已存在該使用者的資料
      $checkSql = "SELECT user FROM `user` WHERE user = ?";
      $stmtCheck = $conn->prepare($checkSql);
      $stmtCheck->bind_param("s", $userId);
      $stmtCheck->execute();
      $stmtCheck->store_result();
      $rowCount = $stmtCheck->num_rows;
      $stmtCheck->close();

      if ($rowCount > 0) {
          // 存在，使用 UPDATE
          $sql = "UPDATE `user` SET image = ? WHERE user = ?";
          $stmt = $conn->prepare($sql);
          if ($stmt === false) {
              die("資料庫錯誤：準備查詢語句失敗 - " . $conn->error);
          }
          // 直接用 "s" 綁定二進位資料（檔案不大時可這麼做）
          $stmt->bind_param("ss", $fileData, $userId);

          if ($stmt->execute()) {
              $stmt->close();
              echo "<script>
                      alert('圖片上傳並儲存成功！');
                      window.location.href = '/~HCHJ/Home/contact1-04.php';
                    </script>";
              exit;
          } else {
              $stmt->close();
              echo "<script>
                      alert('更新失敗！');
                      window.location.href = '/~HCHJ/Home/contact1-04.php';
                    </script>";
              exit;
          }
      } else {
          // 不存在，使用 INSERT
          $sql = "INSERT INTO `user` (user, image) VALUES (?, ?)";
          $stmt = $conn->prepare($sql);
          if ($stmt === false) {
              die("資料庫錯誤：準備查詢語句失敗 - " . $conn->error);
          }
          $stmt->bind_param("ss", $userId, $fileData);

          if ($stmt->execute()) {
              $stmt->close();
              echo "<script>
                      alert('圖片上傳並儲存成功！');
                      window.location.href = '/~HCHJ/Home/contact1-04.php';
                    </script>";
              exit;
          } else {
              $stmt->close();
              echo "<script>
                      alert('插入失敗！');
                      window.location.href = '/~HCHJ/Home/contact1-04.php';
                    </script>";
              exit;
          }
      }
  } else {
      $errorCode = $_FILES['image']['error'];
      echo "<script>
              alert('檔案上傳錯誤，錯誤代碼: $errorCode');
              window.location.href = '/~HCHJ/Home/contact1-04.php';
            </script>";
      exit;
  }
} else {
    echo "<script>
            alert('無法接收到檔案');
            window.location.href = '/~HCHJ/Home/contact1-04.php';
          </script>";
    exit;
}

$conn->close();
?>
