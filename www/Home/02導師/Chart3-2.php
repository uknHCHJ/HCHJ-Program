<?php
session_start(); // 確保有 session

// 顯示錯誤（測試用，正式環境可關閉）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 設定 JSON 標頭
header('Content-Type: application/json');

// 資料庫連線設定
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

// 確保老師已登入
if (!isset($_SESSION['user']['class'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

$teacher_class = $_SESSION['user']['class']; // 從 Session 取得老師的班級

// 如果有 `school` 參數，查詢該學校的科系統計
if (isset($_GET['school'])) {
    $school = $conn->real_escape_string($_GET['school']);

    $sql = "SELECT a.department_name, COUNT(*) as student_count 
            FROM Apply a
            JOIN user u ON a.student_user = u.user
            WHERE u.class = ? AND a.school_name = ?
            GROUP BY a.department_name
            ORDER BY student_count DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $teacher_class, $school);
} else {
    // 查詢該班級的學校志願資料
    $sql = "SELECT a.school_name, COUNT(*) as student_count 
            FROM Apply a
            JOIN user u ON a .student_user = u.user
            WHERE u.class = ?
            GROUP BY a.school_name
            ORDER BY student_count DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $teacher_class);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];

while ($row = $result->fetch_assoc()) {
    if (isset($_GET['school'])) {
        // 回傳科系統計
        $data[] = [
            'Department' => $row['department_name'],
            'StudentCount' => $row['student_count']
        ];
    } else {
        // 回傳學校統計
        $data[] = [
            'School' => $row['school_name'],
            'StudentCount' => $row['student_count']
        ];
    }
}

// 如果沒有資料，回傳提示訊息
if (empty($data)) {
    echo json_encode(['message' => 'No data found']);
} else {
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

$stmt->close();
$conn->close();
?>
