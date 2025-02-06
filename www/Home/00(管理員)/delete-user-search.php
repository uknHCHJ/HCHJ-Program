<?php
session_start();
$conn = mysqli_connect('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');

if (!$conn) {
    die(json_encode(["error" => "資料庫連線失敗：" . mysqli_connect_error()]));
}

// 確保使用者已登入
if (!isset($_SESSION['user'])) {
    echo json_encode(["error" => "未登入，請先登入後再操作"]);
    exit();
}

// 接收 `user` 值，支援 `GET` 和 `POST`
$username = isset($_REQUEST['user']) ? trim($_REQUEST['user']) : '';

if (empty($username)) {
    echo json_encode(["error" => "未提供有效的使用者名稱"]);
    exit();
}

// 確認使用者是否存在
$check_sql = "SELECT * FROM user WHERE user = ?";
$check_stmt = mysqli_prepare($conn, $check_sql);

if ($check_stmt) {
    mysqli_stmt_bind_param($check_stmt, 's', $username);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) === 0) {
        echo json_encode(["error" => "使用者 $username 不存在"]);
        exit();
    }
    mysqli_stmt_close($check_stmt);
}

// 執行刪除
$sql = "DELETE FROM user WHERE user = ?";
$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 's', $username);
    if (mysqli_stmt_execute($stmt)) {
         echo "<script>
        alert('使用者 $username 已成功刪除');
        window.location.href = 'Access-Control1.php';
        </script>";
    } else {
        echo json_encode(["error" => "刪除失敗：" . mysqli_error($conn)]);
    }
    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["error" => "SQL 準備失敗：" . mysqli_error($conn)]);
}

mysqli_close($conn);

?>
