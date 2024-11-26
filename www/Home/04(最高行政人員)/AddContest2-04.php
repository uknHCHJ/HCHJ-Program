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
    $displayEndDate = $_POST['display_end_time']; // 從表單取得顯示截止日期
    
    // 驗證連結格式
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        echo "<script>alert('請輸入有效的網址。');</script>";
        header("Location: create-03.php");
        exit;
    }

    // 取得圖片
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageData = file_get_contents($imageTmpName);
    } else {
        echo "<script>alert('請選擇一個有效的圖片檔案。');</script>";
        header("Location: create-03.php");
        exit;
    }

    // 插入比賽資訊到資料庫
    $sql = "INSERT INTO information (name, inform, link, image, display_end_time) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $inform, $link, $imageData, $displayEndDate);

    if ($stmt->execute()) {
        echo "<script>alert('比賽資訊新增成功！');</script>";
        header("Location:Contestblog1-04.php");
        exit;
    } else {
        echo "<script>alert('新增失敗，請檢查資料格式。');</script>";
        header("Location:AddContest1-04.php");
        exit;
    }

    // 關閉語句
    $stmt->close();
}

// 關閉資料庫連線
$conn->close();
?>