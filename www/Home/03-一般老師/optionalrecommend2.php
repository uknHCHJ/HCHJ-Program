<?php
// 資料庫連接設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 獲取表單資料
$user = $_POST['user'];
$subject_name = $_POST['subject_name'];
$score = $_POST['score'];

// 插入資料到 user_scores 表
$sql = "INSERT INTO user_scores (user, subject_name, score)
        VALUES ('$user', '$subject_name', '$score')";

// 執行 SQL 查詢
if ($conn->query($sql) === TRUE) {
    echo "成績提交成功！";
} else {
    echo "錯誤: " . $sql . "<br>" . $conn->error;
}

// 關閉資料庫連接
$conn->close();
?>