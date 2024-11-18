<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

// 檢查是否有提供檔案 ID
if (isset($_POST['file_id'])) {
    $file_id = intval($_POST['file_id']); // 取得並檢查檔案 ID

    // 從資料庫查詢檔案資訊
    $stmt = $link->prepare("SELECT name, type, data FROM file WHERE id = ?");
    if (!$stmt) {
        die("資料庫查詢準備失敗：" . $link->error);
    }
    
    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $type, $data);
        $stmt->fetch();

        // 設定 HTTP 標頭進行檔案下載
        header("Content-Description: File Transfer");
        header("Content-Type: " . $type);
        header("Content-Disposition: attachment; filename=" . basename($name));
        header("Content-Length: " . strlen($data));
        header("Pragma: public");

        // 清空緩衝區並輸出檔案資料
        ob_clean();
        flush();
        echo $data;
        exit();
    } else {
        echo "檔案不存在或已刪除";
    }
    $stmt->close();
} else {
    echo "無效的檔案 ID";
}

// 關閉資料庫連接
$link->close();
?>
