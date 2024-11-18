<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);

// 檢查連接是否成功
if (!$link) {
    // 輸出錯誤訊息並以 JSON 格式回應
    $response[0] = "無法連接資料庫：" . mysqli_connect_error();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

session_start();
$userData = $_SESSION['user']; 
$userId = $userData['user']; 
$username = $userData['name']; 

// 使用 GET 獲取競賽名稱
if (isset($_GET['competition'])) {
    $competitionName = mysqli_real_escape_string($link, $_GET['competition']); // 確保安全性

    // 從資料庫獲取該比賽的圖片資料
    $sql = "SELECT img FROM history WHERE user = '$userId' AND name = '$competitionName'";  // 根據用戶和競賽名稱查詢
    $result = mysqli_query($link, $sql); // 使用正確的變數連接

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row && !empty($row['img'])) {
            $imageData = $row['img'];

            // 設定適當的 HTTP header 來顯示圖片
            header('Content-Description: File Transfer');
            header('Content-Type: image/jpeg'); // 根據實際圖片格式修改這裡
            header('Content-Disposition: attachment; filename="image.jpg"'); // 修改檔名或格式
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($imageData));
            echo $imageData; // 輸出二進位資料
            exit();
        } else {
            echo "圖片未找到或不存在";
        }
    } else {
        // 增加更多的上下文
        echo "查詢錯誤: " . mysqli_error($link) . "，SQL: " . $sql; // 輸出錯誤訊息和 SQL 語句
    }
} else {
    echo "未提供競賽名稱";
}

?>
