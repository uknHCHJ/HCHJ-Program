<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 檢查是否有提交表單
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得表單資料
    $name = $_POST["name"];
    $inform = $_POST["inform"];
    $link = $_POST["link"];
    
    // 驗證連結格式：檢查是否為合法的 URL
    if (!filter_var($link, FILTER_VALIDATE_URL)) { 
        echo "<script>alert('請輸入有效的網址。');</script>";
        header("Location: AddContest1.php");
        exit;
    }
    
    // 自動設定上傳時間為目前時間
    $uploadTime = date('Y-m-d H:i:s');
    
    // 自動設定結束時間為一年後
    $displayEndDate = date('Y-m-d H:i:s', strtotime('+1 year'));
    
    // 插入比賽資訊到資料庫（假設資料表中有 upload_time 欄位）
    $sql = "INSERT INTO information (name, link, display_end_time, start_time) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $link, $displayEndDate, $uploadTime);

    if ($stmt->execute()) {
        echo "<script>alert('比賽資訊新增成功！');</script>";
        header("Location:Contestblog1.php");
        exit;
    } else {
        echo "<script>alert('新增失敗，請檢查資料格式。');</script>";
        header("Location:AddContest1.php");
        exit;
    }

    // 關閉語句
    $stmt->close();
    // 關閉資料庫連線
    $conn->close();
}
?>
