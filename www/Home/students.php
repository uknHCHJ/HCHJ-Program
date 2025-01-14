<?php
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";
$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$department_id = $_GET['ID'];

$sql = "SELECT u.name AS student_name
        FROM Preferences p
        JOIN user u ON p.user = u.user
        WHERE p.department_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $department_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

$stmt->close();
$conn->close();

echo json_encode($students);
?>
