// get_departments.php
<?php
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$school_id = $_GET['school_id'];

// 查詢該校科系及人數
$sql = " SELECT d.department_name, COUNT(p.user) as count
    FROM Preferences p
    JOIN department d ON p.department_id = d.ID
    WHERE p.school_id = ?
    GROUP BY d.ID
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

echo json_encode($departments);
$conn->close();
?>
