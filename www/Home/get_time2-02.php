<?php
// 設定回傳的內容型別為 JSON
header('Content-Type: application/json');

// 資料庫連接參數
$servername = "127.0.0.1";  // 資料庫伺服器地址
$dbUser = "HCHJ";            // 資料庫使用者名稱
$dbPassword = "xx435kKHq";   // 資料庫密碼
$dbname = "HCHJ";            // 資料庫名稱

// 創建資料庫連接
$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

// 檢查資料庫連接是否成功
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// 查詢 `set_time` 表中的開放時間與結束時間
$sql = "SELECT open_time, close_time FROM set_time LIMIT 1";  // 假設表格中只有一條資料
$result = $conn->query($sql);

// 檢查資料是否存在
if ($result->num_rows > 0) {
    // 取得資料並格式化時間
    $row = $result->fetch_assoc();
    $open_time = $row['open_time'];
    $close_time = $row['close_time'];

    // 回傳時間資料，並將時間轉換為 ISO 格式
    echo json_encode([
        "open_time" => $open_time,
        "close_time" => $close_time
    ]);
} else {
    // 若無資料，回傳錯誤訊息
    echo json_encode(["error" => "No time settings found"]);
}

// 關閉資料庫連接
$conn->close();
?>
