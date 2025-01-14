<?php
session_start();

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

header('Content-Type: application/json');

$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Connection failed: ' . $conn->connect_error]));
}

// 檢查請求類型
if (!isset($_GET['action'])) {
    echo json_encode(['error' => 'No action specified']);
    $conn->close();
    exit();
}

$action = $_GET['action'];

if ($action === 'getSchools') {
    // 獲取每間學校的學生數量
    $sql = "SELECT 
                p.school_id, 
                COUNT(p.user) AS student_count
            FROM Preferences p
            GROUP BY p.school_id";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);

} elseif ($action === 'getSchoolDetails') {
    // 獲取指定學校的學生姓名
    if (!isset($_GET['school_id'])) {
        echo json_encode(['error' => 'No school_id provided']);
        $conn->close();
        exit();
    }

    $school_id = intval($_GET['school_id']); // 確保安全
    $sql = 'SELECT 
                u.name AS student_name,
                p.department_id
            FROM Preferences p
            JOIN user u ON p.user = u.user
            WHERE p.school_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $school_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);

} else {
    echo json_encode(['error' => 'Invalid action']);
}

$conn->close();
?>