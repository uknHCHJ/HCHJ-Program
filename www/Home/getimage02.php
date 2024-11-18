<?php
// 資料庫連接
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 確保接收到正確的 user ID
if (isset($_POST['user'])) {
    $file_id = intval($_POST['user']);
    $sql = "SELECT img FROM history WHERE user = '$file_id' LIMIT";
    $result = mysqli_query($link, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        //header("Content-Type: image/jpeg");  // 可依據實際圖片格式調整
        echo $row['img'];
    } else {
        echo '圖片未找到。';
    }
} else {
    echo '無效的圖片請求。';
}
mysqli_close($link);
?>
