<?php
// 資料庫連線
$mysqli = new mysqli('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');
if ($mysqli->connect_error) {
    die("資料庫連線失敗：" . $mysqli->connect_error);
}

$action = $_GET['action'] ?? '';

if ($action === 'getSchools') {
    // 查詢學校及其被選擇人數
    $sql = "
        SELECT 
            s.school_id,
            s.school_name,
            COUNT(p.user) AS student_count
        FROM 
            Schools s
        LEFT JOIN 
            Preferences p ON s.school_id = p.school_id
        GROUP BY 
            s.school_id, s.school_name
        ORDER BY 
            student_count DESC
    ";
    $result = $mysqli->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);

} elseif ($action === 'getDepartments') {
    // 查詢指定學校內的科系及人數
    $school_id = intval($_GET['school_id']);
    $sql = "
        SELECT 
            p.department_id,
            COUNT(p.user) AS student_count
        FROM 
            Preferences p
        WHERE 
            p.school_id = $school_id
        GROUP BY 
            p.department_id
        ORDER BY 
            student_count DESC
    ";
    $result = $mysqli->query($sql);
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

$mysqli->close();
?>
