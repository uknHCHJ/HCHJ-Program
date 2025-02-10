<?php
session_start();
$servername = "127.0.0.1"; // 伺服器 IP 或 localhost
$username   = "HCHJ";     // 登入帳號
$password   = "xx435kKHq"; // 密碼
$dbname     = "HCHJ";     // 資料庫名稱

// 確保 SESSION 中有儲存唯一識別使用者的資訊
$userData = $_SESSION['user'];
$userId   = $userData['user'];

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 查詢學校資料，並確保依照 school_id 排序以便連續比較
$sql = "SELECT school_id, school_name FROM School_Department ORDER BY school_id";
$result = $conn->query($sql);

$schools = [];
$previousRow = null; // 用來儲存前一筆資料
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 若前一筆資料存在，且其 school_id 與 school_name 與目前資料相同，則跳過這筆資料
        if ($previousRow !== null &&
            $previousRow['school_id'] == $row['school_id'] &&
            $previousRow['school_name'] == $row['school_name']) {
            continue;
        }
        $schools[] = $row;
        $previousRow = $row; // 更新前一筆資料
    }
}

// 輸出結果（JSON 格式）
echo json_encode($schools);
$conn->close();
?>
