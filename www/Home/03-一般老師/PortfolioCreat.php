<?php
// 資料庫連線設定
$servername = "127.0.0.1"; //伺服器IP或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料庫名稱

// 建立連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 從前端表單接收學生 ID 和資料類型
    $category = $_POST['category'];
    $student_id = $_POST['student_id']; // 從表單接收學生 ID

    // 檢查是否有檔案被選擇並且沒有錯誤
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // 取得檔案的內容
        $file_content = file_get_contents($_FILES['file']['tmp_name']);
        $file_name = $_FILES['file']['name'];

        // SQL 插入指令，將檔案資料儲存到資料庫
        $sql = "INSERT INTO portfolio (student_id, category, file_name, file_content) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $student_id, $category, $file_name, $file_content);

        // 執行 SQL 插入操作
        if ($stmt->execute()) {
            echo "檔案上傳成功！";
            header("Location: Portfolio1.php"); // 上傳成功後重定向回檔案管理頁面
        } else {
            echo "檔案上傳失敗！";
        }
    } else {
        echo "請選擇檔案！";
    }
}

$conn->close();
?>
