<?php
session_start();
include 'db.php';

// 檢查是否已登入
if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.html");
    exit();
}

$userData = $_SESSION['user']; // 從 SESSION 獲取用戶資料
$userId = htmlspecialchars($userData['user'], ENT_QUOTES, 'UTF-8'); // 確保數據安全

// 確認是否有 student_id
if (isset($_GET['student_id'])) {
    $student_id = $_GET['student_id']; // 或從 URL 傳遞的 GET 參數
} else {
    echo "學生ID未提供";
    exit();
}

// 資料庫連接
$host = '127.0.0.1';
$db = 'HCHJ';
$user = 'HCHJ';
$pass = 'xx435kKHq';
$link = mysqli_connect($host, $user, $pass, $db);

if (!$link) {
    die("連接資料庫失敗: " . mysqli_connect_error());
}

// 查詢該學生的統測成績
$query = "SELECT * FROM user_scores WHERE user = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $student_id); // 綁定學生ID
$stmt->execute();
$result = $stmt->get_result();

$userScores = [];
while ($row = $result->fetch_assoc()) {
    $userScores[$row['subject_name']] = $row['score'];
}
$stmt->close();

// 根據學校要求的成績計算推薦機率
$query = "SELECT * FROM weighted";
$result = mysqli_query($link, $query);

$recommendations = [];
while ($row = mysqli_fetch_assoc($result)) {
    $schoolName = $row['school_name'];
    $requiredScores = json_decode($row['required_scores'], true); // 解碼 JSON 格式的要求成績

    $matchedCriteria = 0;
    $totalCriteria = count($requiredScores);

    // 計算符合條件的科目數
    foreach ($requiredScores as $subject => $requiredScore) {
        if (isset($userScores[$subject]) && $userScores[$subject] >= $requiredScore) {
            $matchedCriteria++;
        }
    }

    // 計算推薦機率
    $probability = ($matchedCriteria / $totalCriteria) * 100;

    $recommendations[] = [
        'school_name' => $schoolName,
        'probability' => $probability
    ];
}

// 排序推薦學校，根據機率降序排列
usort($recommendations, function ($a, $b) {
    return $b['probability'] - $a['probability'];
});

// 回傳 JSON 給前端
echo json_encode($recommendations);
?>