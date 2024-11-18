<?php
session_start();  // 確保啟動 session

$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    echo "無法連接資料庫：" . mysqli_connect_error();
    exit();  // 無法連接，結束整個程式
}

// 獲取使用者輸入
$user = mysqli_real_escape_string($link, $_POST['username']);
$pass = $_POST['password'];  // 這裡不用 escape，因為我們會用 `password_verify()`

// 查詢使用者資料
$sql = "SELECT * FROM user WHERE user='$user'";
$result = mysqli_query($link, $sql);

// 假如有資料，取得使用者資料
if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);  // 取得使用者資料
    $stored_hashed_password = $userData['password'];  // 從資料庫取得加密過的密碼
    $permissions = $userData['Permissions'];  // 取得權限

    // 使用 `password_verify()` 驗證密碼是否正確
    if (password_verify($pass, $stored_hashed_password)) {
        // 儲存使用者資料到 Session
        $_SESSION['user'] = $userData;

        // 根據權限重定向
        if ($permissions == 4) {
            header("Location:/~HCHJ/Home/index-04.php");
        } elseif ($permissions == 1) {
            header("Location:/~HCHJ/Home/index-01.php");
        } elseif ($permissions == 2) {
            header("Location:/~HCHJ/Home/index-02.php");
        } elseif ($permissions == 3) {
            header("Location:/~HCHJ/Home/index-03.php");
        } else {
            header("Location:/~HCHJ/Home/index-0.php");
        }
        exit();
    } else {
        // 密碼不正確
        echo '<script>
                alert("帳號或密碼錯誤！");
                window.location.href = "index.html"; 
              </script>';
        exit();
    }
} else {
    // 帳號不存在
    echo '<script>
            alert("帳號或密碼錯誤！");
            window.location.href = "index.html"; 
          </script>';
    exit();
}
?>
