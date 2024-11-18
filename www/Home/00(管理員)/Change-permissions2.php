<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 使用 MySQLi 連接資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link){
    mysqli_query($link, 'SET NAMES UTF8'); // 設置字符集
} else {
    echo "無法連接資料庫</br>" . mysqli_connect_error();
    exit;
}

// 檢查表單是否已提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['user'];
    $new_permission = $_POST['new_permission'];
    $new_permission2 = $_POST['new_permission2'];
    $new_permission3 = $_POST['new_permission3'];
    $totalPermissions = $new_permission . ',' . $new_permission2. ',' . $new_permission3; 
    // 更新資料庫中的權限
    $sql = "UPDATE user SET Permissions = ? WHERE user = ?";
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
        // 綁定參數並執行語句
        mysqli_stmt_bind_param($stmt, 'ss', $totalPermissions, $user);
        mysqli_stmt_execute($stmt);

        // 檢查是否更新成功
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // 重定向回權限管理頁面
            header('Location: Access-Control1.php');
            exit();
        } else {
            echo  "<script>alert('更新失敗或沒有改變任何資料'); </script>";
            header('Location: Access-Control1.php');
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "語句準備失敗：" . mysqli_error($link);
    }
}

// 關閉資料庫連接
mysqli_close($link);
?>
