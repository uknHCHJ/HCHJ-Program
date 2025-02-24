<?php
session_start();
$servername = "127.0.0.1";   
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

if (!isset($_SESSION['user'])) {
    echo "<script>
            alert('請先登入！');
            window.location.href = '/~HCHJ/index-03.php'; 
          </script>";
    exit();
}

$userData = $_SESSION['user'];
$student_id = isset($userData['user']) ? intval($userData['user']) : 0;

if ($student_id === 0) {
    die("用戶資料錯誤，請重新登入！");
    header("Location: Portfolio1.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    
    if ($category == "autobiography") {
        $title = $conn->real_escape_string($_POST['title']);
        $content = $conn->real_escape_string($_POST['content']);
        $sql = "INSERT INTO portfolio (student_id, category, file_name, autobiography_content, upload_time) VALUES (?, '自傳', ?, ?, NOW())";
    } else {
        $title = $conn->real_escape_string($_POST['planTitle']);
        $content = $conn->real_escape_string($_POST['planContent']);
        $sql = "INSERT INTO portfolio (student_id, category, file_name, autobiography_content, upload_time) VALUES (?, '讀書計畫', ?, ?, NOW())";
    }
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("iss", $student_id, $title, $content);
        if ($stmt->execute()) {
            echo '上傳成功！';
            header("Location: Portfolio1.php");
        } else {
            echo "上傳失敗：" . $stmt->error;
            header("Location: Portfolio1.php");
        }
        $stmt->close();
    } else {
        echo "錯誤：" . $conn->error;
        header("Location: Portfolio1.php");
    }
}
$conn->close();
?>
