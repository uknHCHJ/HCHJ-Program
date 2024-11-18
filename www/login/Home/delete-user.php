<?php
$conn = mysqli_connect('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');

if (!$conn) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        // 準備刪除使用者的 SQL 語句
        $sql = "DELETE FROM user WHERE user = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, 's', $username);

            if (mysqli_stmt_execute($stmt)) {
                echo "使用者 $username 已刪除成功";
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
