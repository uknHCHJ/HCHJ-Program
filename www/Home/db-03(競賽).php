<?php
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "root", "", "testdb");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    echo "資料庫連接失敗: " . mysqli_connect_error();
}
?>
