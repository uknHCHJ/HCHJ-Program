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
    exit();  // 無法連接，結束程式整個程式。
}

// 獲取使用者輸入
$user = mysqli_real_escape_string($link, $_POST['username']);
$pass = mysqli_real_escape_string($link, $_POST['password']);

// 查詢使用者資料
$sql = "SELECT * FROM user WHERE user='$user' AND password='$pass'";
$result = mysqli_query($link, $sql);

// 假如有資料，用陣列取出使用者資料。
if ($result && mysqli_num_rows($result) > 0) {
    $userData = mysqli_fetch_assoc($result);  // 取得使用者資料
    $permissions = explode(',', $userData['Permissions']);  // 假設權限存為逗號分隔的字串

    // 儲存使用者資料到 Session
    $_SESSION['user'] = $userData;


    if (count($permissions) > 1) {
        $_SESSION['permissions'] = $permissions;
        header("Location: Permission.php");
    }



    elseif (in_array('9', $permissions)) {
        $permission_page = "/~HCHJ/Home/index-0" . $permissions[0] . ".php";
        header("Location: " . $permission_page);

    } 
    exit();
} else {
    echo '<script>
            alert("帳號或密碼錯誤！");
            window.location.href = "index.html"; 
          </script>';
    exit();
}
?>
