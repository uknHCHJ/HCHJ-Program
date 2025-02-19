<?php
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "資料庫連線失敗：" . $conn->connect_error]));
}

$student_id = $_POST["student_id"];
$category = $_POST["category"];
$name = $_POST["name"];

$sql = "SELECT COUNT(*) as count FROM portfolio WHERE student_id = ? AND category = ? AND (file_name = ? OR certificate_name = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $student_id, $category, $name, $name);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

echo json_encode(["exists" => $result["count"] > 0]);

$stmt->close();
$conn->close();
?>
