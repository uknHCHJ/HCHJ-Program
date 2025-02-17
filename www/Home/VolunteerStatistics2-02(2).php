<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null; // 檢查 session 是否有效
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

// 抓取資料，這裡使用 LEFT JOIN 來確保未填選志願的學生也會被顯示出來
$sql = "SELECT 
        u.user AS student_user,
        u.name AS student_name,
        p.school_name,
        p.department_name,
        p.preference_rank
    FROM user u
    LEFT JOIN preferences p ON u.user = p.student_user -- 如果沒有志願資料，也會顯示出來
    WHERE u.class = ? AND u.grade = ? AND (u.Permissions = 1 OR u.Permissions = 9) -- 過濾年級、班級與權限
    ORDER BY u.user, p.preference_rank
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
