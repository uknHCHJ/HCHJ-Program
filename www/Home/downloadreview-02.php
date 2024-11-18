<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 檢查是否有提供檔案 ID
if (isset($_POST['user']) && !empty($_POST['user'])) {
    $file_id = intval($_POST['user']); // 取得並檢查檔案 ID

    // 從資料庫查詢檔案資訊
    $stmt = $link->prepare("SELECT name, type, data FROM file WHERE user = ? ORDER BY upload_date DESC LIMIT 1");
    if (!$stmt) {
        die("資料庫查詢準備失敗：" . $link->error);
    }

    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($name, $type, $data);
        $stmt->fetch();

        // 判斷是否為 PDF
        $disposition = ($type === "application/pdf") ? "inline" : "attachment";

        // 設定 HTTP 標頭
        header("Content-Description: File Transfer");
        header("Content-Type: " . $type);
        header("Content-Disposition: $disposition; filename=" . basename($name));
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
    echo "無效的檔案 ID - 檢查是否有提供有效的 user 值";
}

// 關閉資料庫連接
$link->close();
?>
