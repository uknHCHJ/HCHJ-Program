<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 接收學生分數
$chinese_score = $_POST['chinese_score'];
$english_score = $_POST['english_score'];
$math_score = $_POST['math_score'];
$professional_score = $_POST['professional_score'];

// 計算每所學校的加權總分，並比對門檻
$query = "SELECT school_name, department, total_threshold, 
                 (chinese_weight * :chinese + english_weight * :english + 
                  math_weight * :math + professional_weight * :professional) AS total_score
          FROM schools";

$stmt = $pdo->prepare($query);
$stmt->execute([
    ':chinese' => $chinese_score,
    ':english' => $english_score,
    ':math' => $math_score,
    ':professional' => $professional_score
]);

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 篩選符合的學校
$qualified = [];
$unqualified = [];

foreach ($results as $row) {
    if ($row['total_score'] >= $row['total_threshold']) {
        $qualified[] = $row;
    } else {
        $unqualified[] = $row;
    }
}

// 將結果傳遞到下一頁
session_start();
$_SESSION['qualified'] = $qualified;
$_SESSION['unqualified'] = $unqualified;

header("Location: result.php");
exit();
?>