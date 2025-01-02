<?php
// 資料庫連線
$conn = new mysqli("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
// 確認連線
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 接收表單數據
$chinese = $_POST['chinese'];
$english = $_POST['english'];
$math = $_POST['math'];
$professional = $_POST['professional'];

// 從資料庫讀取學校加權和門檻
$query = "SELECT * FROM schools";
$result = $conn->query($query);

$totalSchools = 0;
$qualifiedSchools = 0;

while ($school = $result->fetch_assoc()) {
    $weightedScore = 
        $chinese * $school['chinese_weight'] +
        $english * $school['english_weight'] +
        $math * $school['math_weight'] +
        $professional * $school['professional_weight'];

    if ($weightedScore >= $school['total_threshold']) {
        $qualifiedSchools++;
    }
    $totalSchools++;
}

// 計算成功百分比
$successPercentage = ($qualifiedSchools / $totalSchools) * 100;

// 將數據傳給前端
$data = [
    "qualified" => $qualifiedSchools,
    "total" => $totalSchools,
    "percentage" => $successPercentage
];

echo json_encode($data);
?>
