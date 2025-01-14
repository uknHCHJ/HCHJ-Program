<?php
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";
$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$school_id = $_GET['school_id'];

$sql = "SELECT p.ID, d.department_name, COUNT(p.user) AS student_count
        FROM Preferences p
        JOIN department d ON p.ID = d.ID
        WHERE p.school_id = ?
        GROUP BY p.ID, d.department_name";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$departments = [];
while ($row = $result->fetch_assoc()) {
    $departments[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($departments);
?>