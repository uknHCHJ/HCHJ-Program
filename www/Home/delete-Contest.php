<?php
session_start();

// 確認 session 中有 user 資料
if (!isset($_SESSION['user'])) {
    die("使用者未登入");
}

$userData = $_SESSION['user']; 
$userId = $userData['user']; // 從 session 中取得 userId

// 建立資料庫連線
$conn = mysqli_connect('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');
if (!$conn) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}
$username = $_POST['username'];
$name = $_POST['name'];
$upload_date = $_POST['upload_date'];
// 偵測是否為 POST 請求，並檢查表單的 name 和 username 是否存在
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['name'])) {
        // 準備 SQL 語句，使用 ? 佔位符以防止 SQL 注入
        $sql = "DELETE FROM history WHERE name = ?  AND user = ? AND upload_date = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // 綁定參數：name 為字串 (s)，userId 為整數 (i)
            mysqli_stmt_bind_param($stmt, 'sis', $name, $userId, $upload_date);

            // 執行 SQL 語句
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
        echo "未提供使用者名稱或競賽名稱";
    }
} else {
    echo "無效的請求方法";
}
?>
