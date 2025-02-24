<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null;
$username = $userData['name'] ?? null;
$grade = $userData['grade']; // 老師的年級
$class = $userData['class']; // 老師的班級

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

// 檢查連線
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit;
}

// 調整 SQL 查詢
$sql = "SELECT 
        p.school_name AS School, 
        p.department_name AS Department, 
        COUNT(u.name) AS StudentCount, 
        GROUP_CONCAT(u.name SEPARATOR ', ') AS Students  
    FROM Preferences p
    JOIN user u ON p.student_user = u.user  
    WHERE u.class = ? AND u.grade = ? AND (u.Permissions = 1 OR u.Permissions = 9) -- 過濾班級和年級
    GROUP BY p.school_name, p.department_name  
    ORDER BY p.school_name, p.department_name
";

// 準備查詢語句並綁定參數
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $class, $grade); // 綁定班級與年級參數

$stmt->execute();
$result = $stmt->get_result();

$data = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
} else {
    $data = []; // 確保回傳空陣列而不是 null
}

// 回傳 JSON
header('Content-Type: application/json');
echo json_encode($data);

$stmt->close();
$conn->close();
?>
