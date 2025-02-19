<?php
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'HCHJ';
$username = 'HCHJ';
$password = 'xx435kKHq';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => '資料庫連線失敗']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'];
$category = $data['category'];
$file_name = $data['file_name'];

$query = "SELECT COUNT(*) FROM portfolio WHERE student_id = ? AND category = ? AND file_name = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$student_id, $category, $file_name]);
$exists = $stmt->fetchColumn() > 0;

echo json_encode(['exists' => $exists]);
?>
