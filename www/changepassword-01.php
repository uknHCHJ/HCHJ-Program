<?php
// 資料庫連線
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 將HTML的ID設成變數在PHP執行
$user = $_POST['user'];
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

    // 使用 password_verify() 檢查舊密碼是否正確||password_verify($oldpass, $stored_hashed_password
    if ($oldpass== $stored_hashed_password )  {
        // 更新為新密碼的哈希值
        $SQL = "UPDATE `user` SET `password` = '$newpass' WHERE `user` = '$user'";
        if (mysqli_query($link, $SQL)) {
            echo "<script>alert('密碼修改完成，請重新登入'); window.location.href = 'index.html';</script>";
        }
    }else{
      echo "<script>alert('舊密碼不正確'); window.location.href = 'changepassword-01.html';</script>";
    }
}
?>
