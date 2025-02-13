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
$grade = $userData['grade'];//學生年級
$class = $userData['class'];//學生班級
$currentUserId = $userData['id'];///學生id
$permissions1 = explode(',', $userData['Permissions']);//把權限拆出來學生
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
        echo " <script>
                  alert('圖片上傳並儲存成功！');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>";

      } else {
        echo " <script>
                  alert('資料庫更新錯誤：');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>" . $stmt->error;

      }

      $stmt->close();
    } else {
      echo " <script>
                  alert('不支援的檔案格式！請選擇 JPG, JPEG, 或 PNG 格式的檔案。');
                  window.location.href = '/~HCHJ/Home/contact01-1.php';
                </script>";
    }
  } else {
    echo " <script>
        alert('檔案上傳錯誤，錯誤代碼：" . $_FILES['image']['error'] . "');
        window.location.href = '/~HCHJ/Home/contact01-1.php';
      </script>";
  }
} else {
  echo " <script>
    alert('無法接收到檔案');
    window.location.href = '/~HCHJ/Home/contact01-1.php';
  </script>";

}
//抓跟學生一樣年級班級的老師，用id判斷先查詢
$sql = "SELECT * FROM `user` WHERE `grade` LIKE '%$grade%' AND `class` LIKE '%$class%' AND `id` != $currentUserId";
$result = mysqli_query($link, $sql);
if ($result) {
  $teachers = [];
  while ($row = mysqli_fetch_assoc($result)) {
    //$permissions2是存老師的權限拆出來個別看有2的人
    $permissions2 = explode(',', $row['Permissions']);
    //如果有2就把查詢出來的老師名字存進去資料庫
    if (in_array('2', $permissions2)) {
      $teachers[] = $row['name'];
    }
  }
} else {
  echo "查詢失敗：" . mysqli_error($link);
}
//如果陣列長度是2那就把老師的名字存進變數
if (count($teachers) == 1) {//只有一個老師
  $teachers1 = "";
  $teachers1 = $teachers[0];
  $sql = "SELECT * FROM `testemail` WHERE `name` LIKE '%$teachers1%'";
  $result = mysqli_query($link, $sql);
  if ($result) {
    $email[] = "";
    while ($row = mysqli_fetch_assoc($result)) {
      $email[] = $row['email'];
    }
  } else {
    echo "查詢失敗：" . mysqli_error($link);
  }
} else {//2個老師
  $teachers1 = "";
  $teachers2 = "";
  $teachers1 = $teachers[0];
  $teachers2 = $teachers[1];
  $sql = "SELECT * FROM `testemail` WHERE name IN ($teachers1, $teachers2)";
  $result = mysqli_query($link, $sql);
  if ($result) {
    $email[] = "";
    while ($row = mysqli_fetch_assoc($result)) {
      $email[] = $row['email'];
    }
  } else {
    echo "查詢失敗：" . mysqli_error($link);
  }
}
if (count($$email[]) == 1) {
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mail = new PHPMailer(true);
    try {
      // 設定 SMTP 伺服器
      $mail->isSMTP();
      $mail->Host = 'smtp.example.com';  // 請替換成你自己的 SMTP 伺服器
      $mail->SMTPAuth = true;
      $mail->Username = 'your_email@example.com'; // 這裡使用你自己的郵件帳號來發送郵件
      $mail->Password = 'your_smtp_password'; // 使用你 SMTP 帳戶的應用程式密碼
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      // 設定發送者與接收者
      $mail->setFrom('your_email@example.com', '系統通知');
      $mail->addAddress('recipient@example.com'); // 這裡填寫接收郵件的地址

      // Email 內容
      $mail->isHTML(true);
      $mail->Subject = "學生資料通知 - " . $_POST['user_name'];
      $mail->Body = "
          <h2>學生資料</h2>
          <p><strong>姓名：</strong> {$_POST['user_name']}</p>
          <p><strong>帳號：</strong> {$_POST['user_email']}</p>
          <p><strong>科系：</strong> {$_POST['user_department']}</p>
          <p><strong>班級：</strong> {$_POST['user_grade']} {$_POST['user_class']}</p>
          <p><strong>負責老師：</strong> $teacherName</p>
        ";

      // 發送郵件
      $mail->send();
      echo "<script>alert('Email 發送成功！'); window.location.href = 'index.php';</script>";
    } catch (Exception $e) {
      echo "<script>alert('Email 發送失敗：" . $mail->ErrorInfo . "'); window.history.back();</script>";
    }
  }
}
$conn->close();
?>