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
    $name = $_POST["school_name"];
    $location = $_POST["location"];
    $inform = $_POST["inform"];
    $link = $_POST["link"];
    
    // 取得圖片
    $imageData = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageData = file_get_contents($imageTmpName);
    } else {
        echo "<script>alert('請選擇一個有效的圖片檔案。');</script>";
        exit;
    }

    // 驗證必填欄位
    if (empty($name) || empty($location) || empty($inform) || empty($link)) {
        echo "<script>alert('請填寫所有必填欄位。');</script>";
        exit;
    }

    // 驗證連結格式
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        echo "<script>alert('請輸入有效的網址。');</script>";
        exit;
    }

    // 準備 SQL 語句
    $sql = "INSERT INTO School (school_name, location, inform, link, image_path) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $location, $inform, $link, $imageData);

    // 執行 SQL 語句並檢查結果
    if ($stmt->execute()) {
        echo "資料更新成功";
        header("Location:Schoolnetwork1-04.php");
    } else {
        echo "新增失敗，請檢查資料格式。";
        header("Location:AddSchool1-04.php");
           }
    // 關閉語句和資料庫連線
    $stmt->close();
}

$conn->close();
?>