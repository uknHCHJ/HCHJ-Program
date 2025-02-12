<?php
// 設定回傳格式為 JSON
header('Content-Type: application/json');
session_start(); // 請確保只呼叫一次 session_start()

// 模擬資料：根據使用者 ID 回傳班級資料
$userClassesData = [
    'user1' => [
        ['id' => 1, 'name' => '班級A'],
        ['id' => 2, 'name' => '班級B']
    ],
    'user2' => [
        ['id' => 3, 'name' => '班級C']
    ]
];

// 功能按鈕清單
$buttons = [
    ['name' => '成績單', 'url' => 'transcript.php'],
    ['name' => '自傳', 'url' => 'autobiography.php'],
    ['name' => '學歷證明', 'url' => 'education_certificate.php'],
    ['name' => '競賽證明', 'url' => 'competition_certificate.php'],
    ['name' => '實習證明', 'url' => 'internship_certificate.php'],
    ['name' => '相關證照', 'url' => 'related_certificates.php'],
    ['name' => '語言能力證明', 'url' => 'language_proficiency.php'],
    ['name' => '專題資料', 'url' => 'project_data.php'],
    ['name' => '其他資料', 'url' => 'other_documents.php'],
    ['name' => '讀書計畫', 'url' => 'study_plan.php'],
    ['name' => '志願查看', 'url' => 'volunteer_view.php']
];

$userID = $_SESSION['user'] ?? 'guest';
if ($userID === 'guest') {
    // 測試時預設使用 user1，確保有班級資料
    $userID = 'user1';
}

$userClasses = $userClassesData[$userID] ?? [];

echo json_encode([
    'classes' => $userClasses,
    'buttons' => $buttons
]);
?>
