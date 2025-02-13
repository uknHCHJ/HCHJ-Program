<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
$studentName = $userData['name'];
$grade = $userData['grade'];  // 學生年級
$class = $userData['class'];  // 學生班級
$currentUserId = $userData['id']; // 學生 id
$permissions1 = explode(',', $userData['Permissions']); // 拆分學生的權限
$sql = "SELECT * FROM `testemail` WHERE `name`='$studentName'";
$result = mysqli_query($conn, $sql);

if ($result) {
  $studentemail = "";
  while ($row = mysqli_fetch_assoc($result)) {
    $studentemail = $row['email'];
  }
}
// 查詢資料庫以確認使用者是否存在
$query = sprintf("SELECT user FROM `user` WHERE user = '%s'", $conn->real_escape_string($userId));
$result = $conn->query($query);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["image"])) {
  if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $fileData = file_get_contents($_FILES['image']['tmp_name']);
      $fileExt = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $allowed = ['jpg', 'jpeg', 'png'];

      if (in_array($fileExt, $allowed)) {
          $sql = "UPDATE user SET image = ? WHERE user = ?";
          $stmt = $conn->prepare($sql);

          if ($stmt === false) {
              die("資料庫錯誤：準備查詢語句失敗 - " . $conn->error);
          }

          // **正確綁定參數**
          $stmt->bind_param("sb", $null, $userId);
          $stmt->send_long_data(0, $fileData); // 傳送二進制數據

          if ($stmt->execute()) {
            echo "<script>
            alert('圖片上傳並儲存成功！');
            window.location.href = '/~HCHJ/Home/contact01-1.php';
          </script>";
              sendEmailToTeacher($grade, $class, $currentUserId, $studentName, $conn);
          } else {
            echo "<script>
            alert('更新失敗！');
            window.location.href = '/~HCHJ/Home/contact01-1.php';
          </script>";
          }

          $stmt->close();
      } else {
        echo "<script>
        alert('不支援此檔案格式！');
        window.location.href = '/~HCHJ/Home/contact01-1.php';
      </script>";
      }
  } else {
    echo "<script>
        alert('檔案上傳錯誤，錯誤代碼: {$_FILES['image']['error']}'');
        window.location.href = '/~HCHJ/Home/contact01-1.php';
      </script>";
     
  }
} else {
  echo "<script>
    alert('無法接收到檔案');
    window.location.href = '/~HCHJ/Home/contact01-1.php';
  </script>";
}
// 🟢 呼叫函式時改成正確的變數名稱
sendEmailToTeacher($grade, $class, $currentUserId, $studentName);
require 'vendor/autoload.php';

function sendEmailToTeacher($grade, $class, $currentUserId, $studentName, $conn) {
  // 查找符合條件的老師 email
  $sql = "SELECT email FROM testemail WHERE name IN (
              SELECT name FROM user WHERE grade LIKE '%$grade%' 
              AND class LIKE '%$class%' 
              AND id != $currentUserId 
              AND FIND_IN_SET('2', Permissions)
          ) LIMIT 1";

  $result = $conn->query($sql);
  if (!$result || $result->num_rows == 0) {
      echo "❌ 找不到老師的 email";
      return;
  }

  $teacheremail = $result->fetch_assoc()['email'];

  // 測試是否正確獲取 email
  if (empty($teacheremail)) {
      echo "❌ SQL 查詢成功，但 email 為空！請檢查資料庫內容。";
      return;
  }

  $mail = new PHPMailer(true);

  try {
      // ✅ SMTP 伺服器設置
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';  // ✅ 請更換為你的 SMTP 伺服器
      $mail->SMTPAuth = true;
      $mail->Username = '109534208@stu.ukn.edu.tw'; // ✅ 請輸入你的郵件帳號
      $mail->Password = 'f230991192';  // ❗ 這裡不能留空！若用 Gmail, 需用 App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // ✅ 郵件收件人 & 內容
      $mail->setFrom('109534209@stu.ukn.edu.tw', '學生系統');
      $mail->addAddress($teacheremail); // 發送給老師
      $mail->isHTML(true);
      $mail->Subject = "學生 $studentName 已更新頭貼";
      $mail->Body = "<h2>學生 $studentName 已更新頭貼</h2>";

      // ✅ 發送郵件
      if ($mail->send()) {
          echo "✅ 郵件已發送！";
      } else {
          echo "❌ 郵件發送失敗: " . $mail->ErrorInfo;
      }

  } catch (Exception $e) {
      echo "❌ 郵件發送失敗: {$mail->ErrorInfo}";
  }
}



$conn->close();
?>