<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$user = $userData['user'];

$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);
$oldpass = $_POST['oldpass'];
$newpass = $_POST['newpass'];

if (empty($oldpass) || empty($newpass)) {
    echo "<script>alert('請輸入所有欄位'); window.location.href = 'changepassword-01.html';</script>";
    exit();
}

//$hashed_password = password_hash($newpass, PASSWORD_DEFAULT);

// 檢查該使用者是否存在，並取出其密碼雜湊
$SQL = "SELECT * FROM user WHERE user = '$user'";
$checkResult = mysqli_query($link, $SQL);

if (mysqli_num_rows($checkResult) > 0) {
    $row = mysqli_fetch_assoc($checkResult);
    $stored_hashed_password = $row['password'];
    if($oldpass==$newpass){
        echo "<script>alert('新舊密碼一樣，更換新密碼'); window.location.href = 'changepassword.html';</script>";
    }
     if ($oldpass== $stored_hashed_password )  {
        // 更新為新密碼的哈希值
        $SQL = "UPDATE `user` SET `password` = '$newpass' WHERE `user` = '$user'";
        if (mysqli_query($link, $SQL)) {
            echo "<script>alert('密碼修改完成，請重新登入'); window.location.href = 'index.html';</script>";
        }
    }else{
      echo "<script>alert('舊密碼不正確'); window.location.href = 'changepassword.html';</script>";
    }
    // 使用 password_verify() 檢查舊密碼是否正確||password_verify($oldpass, $stored_hashed_password
   
}
?>
