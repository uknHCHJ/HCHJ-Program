<?php
// 設定回傳格式為 JSON
header('Content-Type: application/json');
session_start();

$class = urldecode($_GET['class']);
$grade = substr($class, 0, -1); // 取出年級
$realClass = substr($class, -1); // 取出班級

// **動態生成按鈕陣列，包含 grade 和 class**
$buttons = [
    ['name' => '自傳', 'url' => "autobiography.php?grade=$grade&class=$realClass"],
    ['name' => '成績單', 'url' => "transcript.php?grade=$grade&class=$realClass"], 
    ['name' => '學歷證明', 'url' => "diploma.php?grade=$grade&class=$realClass"],
    ['name' => '競賽證明', 'url' => "competition.php?grade=$grade&class=$realClass"],
    ['name' => '實習證明', 'url' => "internship.php?grade=$grade&class=$realClass"],
    ['name' => '專業證照', 'url' => "certifications.php?grade=$grade&class=$realClass"],
    ['name' => '語言能力證明', 'url' => "language.php?grade=$grade&class=$realClass"],
    ['name' => '其他資料', 'url' => "other.php?grade=$grade&class=$realClass"],
    ['name' => '服務證明', 'url' => "Proof-of-service.php?grade=$grade&class=$realClass"],
    ['name' => '讀書計畫', 'url' => "read.php?grade=$grade&class=$realClass"]
];

// **回傳 JSON 給前端**
echo json_encode($buttons);
exit();
?>
