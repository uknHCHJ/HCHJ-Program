<?php
// 接收表單數據並進行基本驗證
$chinese = isset($_POST['chinese']) ? (int)$_POST['chinese'] : 0;
$english = isset($_POST['english']) ? (int)$_POST['english'] : 0;
$math = isset($_POST['math']) ? (int)$_POST['math'] : 0;
$professional = isset($_POST['professional']) ? (int)$_POST['professional'] : 0;

// 計算總分（根據各科目權重）
$total_score = $chinese * 2 + $english * 1.5 + $math * 1.5 + $professional * 3;

// 假設歷年錄取分數線
$previous_cutoff = 600; // 這是一個假設值，實際應根據真實數據

// 計算錄取機率（這裡僅作簡單比較，實際應使用更複雜的統計方法）
$admission_chance = ($total_score >= $previous_cutoff) ? '高' : '低';

// 將結果傳遞給前端顯示
header("Location: SchoolRecommend3.php?chinese=$chinese&english=$english&math=$math&professional=$professional&chance=$admission_chance&score=$total_score");
exit();
?>
