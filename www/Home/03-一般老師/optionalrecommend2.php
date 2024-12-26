<?php
session_start();
include 'db.php';

// 檢查是否已登入
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user'])) {
    die("未登入或用戶資料不存在！");
}

// 從表單獲取資料
$userId = $_POST['user_id']; // 從隱藏欄位取得用戶 ID
$subjectName = $_POST['subject_name']; // 科目名稱
$score = intval($_POST['score']); // 成績，轉換為整數

// 資料檢查
if ($score < 0 || $score > 100) {
    die("成績必須是 0 到 100 之間的有效數字！");
}

// 檢查是否已經提交過該科目的成績
$query = "SELECT * FROM user_scores WHERE user = ? AND subject_name = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("is", $userId, $subjectName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // 如果已經存在相同科目的成績，顯示提示訊息
    echo("<script>
            alert('您已經提交過此科目的成績！');
            window.location.href = 'optionalrecommend1.php'; // 重新導向到成績填寫頁面
          </script>");
    $stmt->close();
    $link->close();
    exit();
}

// 使用準備語句插入資料到資料庫
$stmt = $link->prepare("INSERT INTO user_scores (user, subject_name, score) VALUES (?, ?, ?)");
if (!$stmt) {
    die("資料庫錯誤：" . $link->error);
}

$stmt->bind_param("ssi", $userId, $subjectName, $score);

if ($stmt->execute()) {
    echo("<script>
            alert('成績提交成功！');
            window.location.href = 'optionalrecommend1.php'; // 重新導向到成績填寫頁面
          </script>");
} else {
    echo "提交失敗：" . $stmt->error;
}

// 關閉連線
$stmt->close();
$link->close();
?>
