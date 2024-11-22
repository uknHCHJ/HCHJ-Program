<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');
} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

if (isset($_POST['file_id']) && isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'yes') {
  $file_id = intval($_POST['file_id']);  // 確保文件 ID 取得正確

  // 準備刪除 SQL 語句
  $stmt = $link->prepare("DELETE FROM file WHERE id = '$file_id'");
  if (!$stmt) {
    die("資料庫查詢準備失敗：" . $link->error);
  }
  $stmt->execute();
  if ($stmt->affected_rows > 0) {
    // 刪除成功
    echo "<script>alert('檔案已成功刪除'); window.location.href = '/~HCHJ/Home/recordforreview04-1.php';</script>";
  } else {
    // 刪除失敗
    echo "<script>alert('檔案不存在或刪除失敗'); window.location.href = '/~HCHJ/Home/recordforreview04-1.php';</script>";
  }

  $stmt->close();
}

?>