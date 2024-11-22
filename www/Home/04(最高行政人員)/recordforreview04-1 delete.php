<?php
session_start();

/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}
$userId = isset($_GET['user']) ? $_GET['user'] : null;
// 檢查是否有提供檔案 ID
if (isset($userId)) {


    // 從資料庫查詢檔案資訊
    $stmt = $link->prepare("SELECT name, type, data FROM file WHERE user = ?");
    if (!$stmt) {
        die("資料庫查詢準備失敗：" . $link->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $type, $data);
        $stmt->fetch();

        // 設定檔案資訊並輸出下載
        header("Content-Description: File Transfer");
        header("Content-Type: " . htmlspecialchars($type)); // 避免錯誤輸入
        header("Content-Disposition: attachment; filename=\"" . htmlspecialchars(basename($name)) . "\"");
        header("Content-Length: " . strlen($data));
        header("Pragma: public");

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
