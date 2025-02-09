<?php
session_start();
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱

// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);

//建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

//確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}


// 查詢 Secondskill 表格中的學校名稱
$sql = "SELECT id, name FROM Secondskill";
$result = $conn->query($sql);

$schools = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schools[] = [
            'school_id' => $row['id'],
            'school_name' => $row['name']
        ];
    }
}

echo json_encode($schools);
$conn->close();
?>