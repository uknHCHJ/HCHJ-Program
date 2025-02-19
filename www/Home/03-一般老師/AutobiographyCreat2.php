<?php 
session_start();

// 建立資料庫連線
$servername = "127.0.0.1";   
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("❌ 連線失敗: " . $conn->connect_error);
}

// 檢查是否登入
if (!isset($_SESSION['user'])) {
    echo "<script>
            alert('請先登入！');
            window.location.href = '/~HCHJ/index-03.php'; 
          </script>";
    exit();
}

// 從 SESSION 取得 student_id
$userData = $_SESSION['user'];
$student_id = isset($userData['user']) ? intval($userData['user']) : 0; // 確保 student_id 為數字

// 檢查 student_id 是否有效
if ($student_id === 0) {
    die("❌ 學生 ID 錯誤，請重新登入！");
    header("Location:AutobiographyCreat1.php"); 
}

// 確保表單有提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = isset($_POST['title']) ? $conn->real_escape_string($_POST['title']) : '';
    $content = isset($_POST['content']) ? $conn->real_escape_string($_POST['content']) : '';

    // 檢查資料是否完整
    if (empty($title) || empty($content)) {
        die("❌ 錯誤：標題或內容不得為空！");
        header("Location:AutobiographyCreat1.php"); 
    }

    // 準備 SQL 語法
    $sql = "INSERT INTO portfolio (student_id, category, file_name, autobiography_content, upload_time) 
            VALUES (?, '自傳', ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iss", $student_id, $title, $content);
        if ($stmt->execute()) {
            echo('✅ 自傳提交成功！');
            header("Location:AutobiographyCreat1.php"); 

        } else {
            echo "❌ 提交失敗：" . $stmt->error;
            header("Location:AutobiographyCreat1.php"); 
        }
        $stmt->close();
    } else {
        echo "❌ SQL 錯誤：" . $conn->error;
        header("Location:AutobiographyCreat1.php"); 
    }
}

// 關閉連線
$conn->close();
?>
