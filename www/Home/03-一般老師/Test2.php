<?php
header('Content-Type: application/json');

// 資料庫連線
$conn = new mysqli("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");

// 檢查連線
if ($conn->connect_error) {
    die(json_encode(["error" => "資料庫連線失敗: " . $conn->connect_error]));
}

// 接收數據
$chinese = $_POST['chinese'];
$english = $_POST['english'];
$math = $_POST['math'];
$professional = $_POST['professional'];

// 查詢學校資料
$query = "SELECT * FROM school_thresholds";
$result = $conn->query($query);

$schools = [];

while ($row = $result->fetch_assoc()) {
    $weightedScore = 
        $chinese * $row['chinese_weight'] +
        $english * $row['english_weight'] +
        $math * $row['math_weight'] +
        $professional * $row['professional_weight'];

    $schools[] = [
        "name" => $row['school_name'],
        "weightedScore" => $weightedScore
    ];
}

// 排序學校，依加權分數由高到低
usort($schools, function ($a, $b) {
    return $b['weightedScore'] <=> $a['weightedScore'];
});

// 返回前 6 名學校
$topSchools = array_slice($schools, 0, 6);

echo json_encode(["topSchools" => $topSchools]);

$conn->close();
?>
