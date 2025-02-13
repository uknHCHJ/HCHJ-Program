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
$studentName = $userData['name'];
$grade = $userData['grade'];  // 學生年級
$class = $userData['class'];  // 學生班級
$currentUserId = $userData['id']; // 學生 id
$permissions1 = explode(',', $userData['Permissions']); // 拆分學生的權限
$sql = "SELECT * FROM `testemail` WHERE `name`='$studentName'";
$result = mysqli_query($link, $sql);
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
        echo "<script>
                  alert('圖片上傳並儲存成功！');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>";

        // 頭貼更新後發送郵件給老師
        sendEmailToTeacher($grade, $class, $currentUserId, $name);

      } else {
        echo "<script>
                  alert('資料庫更新錯誤：');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>" . $stmt->error;
      }

      $stmt->close();
    } else {
      echo "<script>
                  alert('不支援的檔案格式！請選擇 JPG, JPEG, 或 PNG 格式的檔案。');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>";
    }
  } else {
    echo "<script>
        alert('檔案上傳錯誤，錯誤代碼：" . $_FILES['image']['error'] . "');
        window.location.href = '/~HCHJ/Home/contact01-1.php';
      </script>";
  }
} else {
  echo "<script>
    alert('無法接收到檔案');
    window.location.href = '/~HCHJ/Home/contact01-1.php';
  </script>";
}
// 頭貼更新後發送郵件給老師
sendEmailToTeacher($grade, $class, $currentUserId, $name);
// 發送郵件給老師
function sendEmailToTeacher($grade, $class, $currentUserId, $studentName)
{
    global $conn;
    $sql = "SELECT * FROM `user` WHERE `grade` LIKE '%$grade%' AND `class` LIKE '%$class%' AND `id` != $currentUserId";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $teachers = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $permissions2 = explode(',', $row['Permissions']);
            if (in_array('2', $permissions2)) {
                $teachers[] = $row['name'];
            }
        }
    } else {
        echo "查詢失敗：" . mysqli_error($conn);
    }

    if (count($teachers) > 0) {
        $teachername = $teachers[0]; // 只取第一個老師
        $sql = "SELECT * FROM `testemail` WHERE `name`='$teachername'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $teacheremail = "";
            while ($row = mysqli_fetch_assoc($result)) {
                $teacheremail = $row['email'];
            }
        }
    }

    // 🔍 **測試 email 是否正確**
    var_dump($teacheremail);
    exit;

    $subject = "學生資料通知 - " . $studentName;
    $message = "<h2>學生資料</h2><p><strong>姓名：</strong> " . $studentName . "</p>";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: 109534209@stu.ukn.edu.tw\r\n";

    if (mail($teacheremail, $subject, $message, $headers)) {
        echo "<script>alert('Email 發送成功！'); window.location.href = '/~HCHJ/Home/contact01-1.php';</script>";
    } else {
        echo "<script>alert('Email 發送失敗，請檢查設定或聯繫系統管理員。'); window.history.back();</script>";
    }
}


$conn->close();
?>