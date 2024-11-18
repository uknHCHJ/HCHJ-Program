<?php
// 資料庫連線參數
$servername = "127.0.0.1"; // 資料庫伺服器地址
$username = "HCHJ"; // 資料庫用戶名
$password = "xx435kKHq"; // 密碼
$dbname = "HCHJ"; // 資料庫名稱

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查資料庫連線
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 確認是否有 user 參數
$user = isset($_GET['user']) ? mysqli_real_escape_string($conn, $_GET['user']) : '';

// 查詢學生的志願序資料
$sql = "SELECT p.serial_number, s.school_name, d.department_name, p.time
        FROM preferences p
        LEFT JOIN school s ON p.school_id = s.school_id
        LEFT JOIN department d ON p.department_id = d.department_id
        WHERE p.user = '$user'
        ORDER BY p.serial_number ASC";

$result = $conn->query($sql);

// 準備返回的資料
$preferences = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $preferences[] = $row;
    }
}

// 關閉資料庫連線
$conn->close();

// 回傳資料為 JSON 格式
echo json_encode(['status' => 'success', 'data' => $preferences]);
?>