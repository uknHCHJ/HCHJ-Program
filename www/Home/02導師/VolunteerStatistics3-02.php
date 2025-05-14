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
    a.school_name AS School, 
    a.department_name AS Department, 
    COUNT(u.name) AS StudentCount, 
    GROUP_CONCAT(u.name SEPARATOR ', ') AS Students,
    t.Application_num AS Quota
FROM Apply a
JOIN user u ON a.student_user = u.user
LEFT JOIN (
    SELECT 
        t.Application_num,
        s.name AS school_name,
        d.department_name AS department_name
    FROM test t
    JOIN school s ON t.school_name = s.name
    JOIN Department d ON t.department = d.department_name
) t ON a.school_name = t.school_name AND a.department_name = t.department_name
WHERE u.class = ? AND u.grade = ?
    AND (u.Permissions = 1 OR u.Permissions = 9)
GROUP BY a.school_name, a.department_name, t.Application_num
ORDER BY a.school_name, a.department_name;
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