<?php
$servername = "127.0.0.1"; //伺服器IP或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料庫名稱

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $sql = "SELECT file_path FROM portfolio WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        unlink($row['file_path']);
    }

    $sql = "DELETE FROM portfolio WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "檔案已刪除！<br><a href='index.php'>返回首頁</a>";
    } else {
        echo "刪除失敗：" . $conn->error;
    }
}

$conn->close();
?>
