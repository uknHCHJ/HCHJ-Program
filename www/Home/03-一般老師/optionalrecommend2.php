<?php
// 資料庫連接設定
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "your_database";

// 建立資料庫連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 獲取表單資料
$user_id = $_POST['user_id'];
$subject_name = $_POST['subject_name'];
$score = $_POST['score'];

// 插入資料到 user_scores 表
$sql = "INSERT INTO user_scores (user_id, subject_name, score)
        VALUES ('$user_id', '$subject_name', '$score')";

// 執行 SQL 查詢
if ($conn->query($sql) === TRUE) {
    echo "成績提交成功！";
} else {
    echo "錯誤: " . $sql . "<br>" . $conn->error;
}

// 關閉資料庫連接
$conn->close();
?>