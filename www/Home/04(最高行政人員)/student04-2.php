<?php
// 設定回傳格式為 JSON
header('Content-Type: application/json');
session_start();
$userData = $_SESSION['user'];
$permissions = explode(",", $userData['Permissions']); // 權限以逗號分隔

$class = urldecode($_GET['class']);
$grade = substr($class, 0, -1); // 取出年級
$realClass = substr($class, -1); // 取出班級

// **動態生成按鈕陣列，包含 grade 和 class**
$buttons = [
    ['name' => '自傳', 'url' => "autobiography-04.php?grade=$grade&class=$realClass"],
    ['name' => '成績單', 'url' => "transcript-04.php?grade=$grade&class=$realClass"], 
    ['name' => '學歷證明', 'url' => "diploma-04.php?grade=$grade&class=$realClass"],
    ['name' => '競賽證明', 'url' => "competition-04.php?grade=$grade&class=$realClass"],
    ['name' => '實習證明', 'url' => "internship-04.php?grade=$grade&class=$realClass"],
    ['name' => '專業證照', 'url' => "certifications-04.php?grade=$grade&class=$realClass"],
    ['name' => '語言能力證明', 'url' => "language-04.php?grade=$grade&class=$realClass"],
    ['name' => '其他資料', 'url' => "other-04.php?grade=$grade&class=$realClass"],
    ['name' => '服務證明', 'url' => "Proof-of-service-04.php?grade=$grade&class=$realClass"],
    ['name' => '讀書計畫', 'url' => "read-04.php?grade=$grade&class=$realClass"]
];

// **回傳 JSON 給前端**
echo json_encode($buttons);
exit();
?>
