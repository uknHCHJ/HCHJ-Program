<?php
session_start();

// 資料庫連線設置
$host = '127.0.0.1'; // 資料庫主機
$dbname = 'HCHJ'; // 資料庫名稱
$username = 'HCHJ'; // 資料庫使用者名稱
$password = 'xx435kKHq'; // 資料庫密碼

// 建立資料庫連線
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo '連線失敗: ' . $e->getMessage();
    exit;
}
// 從 SESSION 取出使用者資訊
$userData = $_SESSION['user'];
$userId = $userData['user'];       // 帳號或學號
$studentName = $userData['name'];
$grade = $userData['grade'];
$class = $userData['class'];
$currentUserId = $userData['id'];          // 學生 id
$permissions1 = explode(',', $userData['Permissions']);

// 若有需要取得學生 email（例如用於通知）
$sql = "SELECT * FROM user WHERE `name`='$studentName'";
$result = mysqli_query($conn, $sql);
if ($result) {
    $studentemail = "";
    while ($row = mysqli_fetch_assoc($result)) {
        $studentemail = $row['email'];
    }
}

// 確認表單是否有送出資料
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 取得 POST 資料
    $student_id = $_POST['student_id'];
    $category = $_POST['category'];
    $organization = $_POST['organization'];
    $certificate_name = $_POST['certificate_name'];

    // 取得使用者輸入的檔名
    $customFileName = trim($_POST['customFileName']); // 去除空白
    $customFileName = !empty($customFileName) ? $customFileName : null; // 確保空字串不被使用

    // 處理上傳檔案
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // 取得檔案資訊
        $file_tmp = $_FILES['file']['tmp_name'];
        $originalFileName = $_FILES['file']['name']; // 原始檔案名稱
        $file_size = $_FILES['file']['size'];
        $file_type = $_FILES['file']['type'];

        // 限制檔案大小和類型
        $allowed_types = ['image/png', 'image/jpeg', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        $max_size = 5 * 1024 * 1024; // 最大 5MB

        if (!in_array($file_type, $allowed_types)) {
            die("檔案類型不正確。只能上傳 PNG, JPG, DOC, DOCX 檔案。");
            header("Location: Portfolio1.php");
        }

        if ($file_size > $max_size) {
            die("檔案過大，請上傳小於 5MB 的檔案。");
            header("Location: Portfolio1.php");
        }

        // 取得副檔名
        $fileExtension = pathinfo($originalFileName, PATHINFO_EXTENSION);

        // 如果使用者有輸入檔名，檢查是否已經包含副檔名
        if ($customFileName) {
            // 使用 pathinfo 檢查是否已有副檔名
            $userInputExtension = pathinfo($customFileName, PATHINFO_EXTENSION);

            if (strtolower($userInputExtension) === strtolower($fileExtension)) {
                // 使用者輸入的檔名已經有副檔名，直接使用
                $file_name = $customFileName;
            } else {
                // 否則，手動加上副檔名
                $file_name = $customFileName . '.' . $fileExtension;
            }
        } else {
            // 沒有輸入則使用原始檔名
            $file_name = $originalFileName;
        }

        // 讀取檔案內容並轉換為二進位格式
        $file_content = file_get_contents($file_tmp);

        // 插入資料庫
        $sql = "INSERT INTO portfolio (student_id, category, file_name, file_content, organization, certificate_name) 
                VALUES (:student_id, :category, :file_name, :file_content, :organization, :certificate_name)";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':file_name', $file_name);
        $stmt->bindParam(':file_content', $file_content, PDO::PARAM_LOB);
        $stmt->bindParam(':organization', $organization);
        $stmt->bindParam(':certificate_name', $certificate_name);

        if ($stmt->execute()) {
            echo "檔案上傳成功！";
            //郵件
            // 構造 SQL，使用 PDO 的 prepared statement
            $sql = "SELECT email FROM user WHERE name IN (SELECT name FROM user WHERE grade LIKE :grade AND class LIKE :class AND id != :currentUserId AND FIND_IN_SET('2', Permissions)) LIMIT 1";
            $stmt = $pdo->prepare($sql);
            // 設定 LIKE 條件參數（包含萬用字元）
            $likeGrade = "%$grade%";
            $likeClass = "%$class%";
            // 綁定參數
            $stmt->bindParam(':grade', $likeGrade);
            $stmt->bindParam(':class', $likeClass);
            $stmt->bindParam(':currentUserId', $student_id, PDO::PARAM_INT);
            // 執行查詢
            $stmt->execute();
            // 取得查詢結果
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$teacher) {
                echo "❌ 找不到老師的 email";
                exit;
            }
            $teacheremail = $teacher['email'];
            if (empty($teacheremail)) {
                echo "❌ SQL 查詢成功，但 email 為空！請檢查資料庫內容。";
                exit;
            }
            $subject = "學生 " . $studentName . " 已上傳備審檔案";
            $message = "<h2>學生 " . $studentName . " 已上傳檔案至系統，請老師查收</h2>";
            // 利用 "姓名 <信箱>" 格式來呈現寄件人資訊，讓老師能直接看到學生姓名
            $headers = "From: $studentname \r\n";
            $headers .= "Reply-To: $studentemail \r\n";
            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

            // 使用 mail() 傳送郵件
            if (mail($teacheremail, $subject, $message, $headers)) {
                echo "✅ 郵件已發送給 {$teacheremail}！";
            } else {
                echo "❌ 郵件發送失敗！請確認 mail() 設定。";
            }
            header("Location: Portfolio1.php");
            exit;
        } else {
            echo "檔案上傳失敗。";
            header("Location: Portfolio1.php");
            exit;
        }
    } else {
        echo "檔案上傳失敗，請檢查檔案。";
        header("Location: Portfolio1.php");
        exit;
    }
} else {
    echo "請透過表單提交資料。";
    header("Location: Portfolio1.php");
    exit;
}
?>