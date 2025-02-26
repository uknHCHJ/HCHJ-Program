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
$studentName = $userData['name'];
$grade = $userData['grade'];
$class = $userData['class'];

if ($student_id === 0) {
    die("用戶資料錯誤，請重新登入！");
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

            // **📌 取得學生 Email**
            $studentEmailQuery = "SELECT email FROM user WHERE id = ?";
            $stmt1 = $conn->prepare($studentEmailQuery);
            $stmt1->bind_param("i", $student_id);
            $stmt1->execute();
            $result1 = $stmt1->get_result();
            $studentData = $result1->fetch_assoc();
            $stmt1->close();
            $studentEmail = !empty($studentData['email']) ? $studentData['email'] : "noreply@yourdomain.com";

            // **📌 取得老師 Email**
            $teacherEmailQuery = "SELECT email FROM user WHERE grade LIKE ? AND class LIKE ? AND id != ? AND FIND_IN_SET('2', Permissions) LIMIT 1";
            $stmt2 = $conn->prepare($teacherEmailQuery);
            $likeGrade = "%$grade%";
            $likeClass = "%$class%";
            $stmt2->bind_param("ssi", $likeGrade, $likeClass, $student_id);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            $teacherData = $result2->fetch_assoc();
            $stmt2->close();
            $teacherEmail = !empty($teacherData['email']) ? $teacherData['email'] : null;

            // **📌 發送郵件**
            if ($teacherEmail) {
                $subject = "學生 {$studentName} 已上傳 " . ($category == "autobiography" ? "自傳" : "讀書計畫");
                $message = "<h2>學生 {$studentName} 已上傳 " . ($category == "autobiography" ? "自傳" : "讀書計畫") . "，請老師查收</h2>";
                $headers = "From: \"{$studentName}\" <{$studentEmail}>\r\n";
                $headers .= "Reply-To: {$studentEmail}\r\n";
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

                if (!mail($teacherEmail, $subject, $message, $headers)) {
                    error_log("❌ 郵件發送失敗，請檢查 mail() 設定。");
                }
            } else {
                error_log("❌ 找不到老師的 Email，郵件未發送。");
            }

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
