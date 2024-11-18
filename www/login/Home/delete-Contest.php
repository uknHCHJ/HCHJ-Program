<?php
session_start();
$userData = $_SESSION['user']; 

$userId = $userData['user']; 
$username=$userData['name']; 

$conn = mysqli_connect('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');

if (!$conn) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['name'])) {
        $name = $_POST['name'];

        // 準備刪除使用者的 SQL 語句
        $sql = "DELETE FROM history WHERE name ='$name'";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);

            if (mysqli_stmt_execute($stmt)) {
                echo "$name 競賽資訊已刪除成功";
            } else {
                echo "刪除失敗：" . mysqli_error($conn);
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "SQL 準備失敗：" . mysqli_error($conn);
        }

        mysqli_close($conn);
    } else {
        echo "未提供使用者名稱";
    }
} else {
    echo "無效的請求方法";
}
?>
