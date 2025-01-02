<?php
// 讀取 POST 傳來的 JSON 資料
$data = json_decode(file_get_contents('php://input'), true);

// 學校資料
$schools = [
    ['name' => '國立臺北商業大學 資訊管理系', 'weights' => [0.2, 0.2, 0.3, 0.3], 'score' => 80, 'isNational' => true],
    ['name' => '國立高雄科技大學 資訊管理系', 'weights' => [0.2, 0.3, 0.2, 0.3], 'score' => 78, 'isNational' => true],
    ['name' => '國立雲林科技大學 資訊管理系', 'weights' => [0.2, 0.2, 0.3, 0.3], 'score' => 76, 'isNational' => true],
    ['name' => '國立中山大學 資訊管理學系', 'weights' => [0.2, 0.3, 0.25, 0.25], 'score' => 76, 'isNational' => true],
    ['name' => '國立臺中科技大學 資訊管理系', 'weights' => [0.2, 0.2, 0.3, 0.3], 'score' => 70, 'isNational' => true],
    ['name' => '國立臺北護理健康大學 資訊管理系', 'weights' => [0.3, 0.2, 0.25, 0.25], 'score' => 70, 'isNational' => true],
    ['name' => '中國科技大學 資訊管理系', 'weights' => [0.25, 0.25, 0.25, 0.25], 'score' => 75, 'isNational' => false],
    ['name' => '崑山科技大學 資訊管理系', 'weights' => [0.2, 0.2, 0.3, 0.3], 'score' => 72, 'isNational' => false],
    ['name' => '南臺科技大學 資訊管理系', 'weights' => [0.2, 0.2, 0.3, 0.3], 'score' => 74, 'isNational' => false],
    ['name' => '勤益科技大學 資訊管理系', 'weights' => [0.25, 0.25, 0.25, 0.25], 'score' => 74, 'isNational' => false],
    ['name' => '致理科技大學 資訊管理系', 'weights' => [0.2, 0.3, 0.3, 0.2], 'score' => 71, 'isNational' => false],
    ['name' => '台北海洋科技大學 資訊管理系', 'weights' => [0.25, 0.25, 0.25, 0.25], 'score' => 67, 'isNational' => false],
    ['name' => '中華科技大學 資訊管理系', 'weights' => [0.25, 0.25, 0.25, 0.25], 'score' => 65, 'isNational' => false],
    ['name' => '虎尾科技大學 資訊管理系', 'weights' => [0.2, 0.3, 0.3, 0.2], 'score' => 73, 'isNational' => false],
    ['name' => '健行科技大學 資訊管理系', 'weights' => [0.2, 0.2, 0.25, 0.35], 'score' => 68, 'isNational' => false],
    ['name' => '國北護大學 資訊管理系', 'weights' => [0.3, 0.2, 0.25, 0.25], 'score' => 70, 'isNational' => false]
];

// 計算每所學校的加權分數
foreach ($schools as $index => $school) {
    $weightedScore = $data['chinese'] * $school['weights'][0] +
                     $data['english'] * $school['weights'][1] +
                     $data['math'] * $school['weights'][2] +
                     $data['professional'] * $school['weights'][3];
    $schools[$index]['calculatedScore'] = $weightedScore;
}

// 根據加權分數和國立學校的優先順序進行排序
usort($schools, function ($a, $b) {
    // 優先考慮國立學校
    if ($a['isNational'] && !$b['isNational']) {
        return -1;
    } elseif (!$a['isNational'] && $b['isNational']) {
        return 1;
    }
    
    // 如果都是國立學校或都是私立學校，則根據加權分數排序
    return $b['calculatedScore'] - $a['calculatedScore'];
});

// 回傳排序後的結果
header('Content-Type: application/json');
echo json_encode($schools);
?>
