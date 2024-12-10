<?php
session_start();
include 'db.php'; // 確保已正確連接資料庫

// 檢查是否已登入
if (!isset($_SESSION['user'])) {
    echo("<script>
            alert('未登入，請先登入！');
            window.location.href = '/~HCHJ/index.html';
          </script>");
    exit();
}

// 獲取表單資料
$userId = $_POST['user_id']; // 與前端隱藏欄位名稱保持一致
$subjectName = $_POST['subject_name'];
$score = intval($_POST['score']);

// 檢查資料合法性
if (empty($userId) || empty($subjectName) || empty($score)) {
    die("所有欄位都是必填的！");
}
if ($score < 0 || $score > 100) {
    die("成績必須是 0 到 100 之間的有效數字！");
}

// 檢查是否已經存在相同用戶和科目的成績
$query = "SELECT * FROM user_scores WHERE user = ? AND subject_name = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("is", $userId, $subjectName);
$stmt->execute();
$result = $stmt->get_result();

// 如果已經存在相同科目的成績，顯示提示訊息
if ($result->num_rows > 0) {
    echo("<script>
            alert('您已經提交過此科目的成績！');
            window.location.href = 'optionalrecommend1.php'; // 重新導向到成績填寫頁面
          </script>");
    exit();
}

// 使用準備語句插入資料到資料庫，防止 SQL 注入
$stmt = $link->prepare("INSERT INTO user_scores (user, subject_name, score) VALUES (?, ?, ?)");
if (!$stmt) {
    die("資料庫錯誤：" . $link->error);
}

$stmt->bind_param("isi", $userId, $subjectName, $score);

if ($stmt->execute()) {
    echo("<script>
            alert('成績提交成功！');
          </script>");
} else {
    echo "提交失敗：" . $stmt->error;
}

// 關閉連線
$stmt->close();
$link->close();
?>