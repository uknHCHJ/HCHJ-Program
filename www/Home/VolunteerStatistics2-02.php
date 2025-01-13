<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

// 資料庫連接設定
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

header('Content-Type: application/json');


$conn = new mysqli($servername, $username, $password, $dbname);

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
    // 獲取學校數據
    $sql = "SELECT school_id, COUNT(*) AS student_count FROM Preferences GROUP BY school_id";
    $result = $conn->query($sql);

    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    echo json_encode($data);

} elseif ($action === 'getDepartments') {
    // 獲取科系和學生數據
    if (!isset($_GET['school_id'])) {
        echo json_encode(['error' => 'No school_id provided']);
        $conn->close();
        exit();
    }

    $school_id = intval($_GET['school_id']); // 確保安全
    $sql = "SELECT 
            d.department_id,
            d.department_name,
            COUNT(p.user_id) AS student_count,
            GROUP_CONCAT(u.user_name SEPARATOR ',') AS students
        FROM Preferences p
        JOIN Department d ON p.department_id = d.department_id
        JOIN user u ON p.user_id = u.user_id
        WHERE p.school_id = ?
        GROUP BY d.department_id, d.department_name";

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