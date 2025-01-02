<?php
// 連接到資料庫
$pdo = new PDO('mysql:host=127.0.0.1;dbname=HCHJ', 'HCHJ', 'xx435kKHq');

// 取得所有學校的資料（包含加權係數）
$schoolsQuery = $pdo->query("SELECT id, name, chinese_weight, english_weight, math_weight, professional_weight FROM school_thresholds");
$schools = $schoolsQuery->fetchAll(PDO::FETCH_ASSOC);

// 取得所有學生的成績
$studentsQuery = $pdo->query("SELECT id, name, chinese, english, math, professional FROM students");
$students = $studentsQuery->fetchAll(PDO::FETCH_ASSOC);

// 計算每所學校的入取機率
$schoolProbabilities = [];
foreach ($schools as $school) {
    $passingCount = 0;

    // 計算每位學生的加權總分
    foreach ($students as $student) {
        $weightedScore = $student['chinese'] * $school['chinese_weight'] +
                         $student['english'] * $school['english_weight'] +
                         $student['math'] * $school['math_weight'] +
                         $student['professional'] * $school['professional_weight'];

        // 假設學校的錄取門檻是加權總分達到300分
        if ($weightedScore >= 300) {
            $passingCount++;
        }
    }

    // 計算學校的入取機率
    $probability = $passingCount / count($students) * 100;
    $schoolProbabilities[] = [
        'name' => $school['name'],
        'probability' => $probability
    ];
}
?>
