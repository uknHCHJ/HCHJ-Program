<?php
// 設定回傳格式為 JSON
header('Content-Type: application/json');
session_start();
 // 請確保只呼叫一次 session_start()

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

// 你的功能按鈕陣列
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
    ['name' => '志願查看', 'onclick' => 'volunteer_view.php'],
    //<button type="button" class="btn btn-secondary" ="window.history.back();">返回上一頁</button>
];

// **檢查是否有資料**
if (empty($buttons)) {
    echo json_encode(["error" => "No buttons found"]);
} else {
    echo json_encode($buttons);
}
exit();
?>
