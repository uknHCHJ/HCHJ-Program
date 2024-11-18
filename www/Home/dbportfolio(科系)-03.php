<?php
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

// 取得提交的選擇數量
$numChoices = isset($_POST['numChoices']) ? intval($_POST['numChoices']) : 0;
$ID = $_POST["school_id"];
$school_id= $_POST["school_id"];

// 檢查是否有選擇提交
if ($numChoices > 0) {
    // 插入到資料庫的表格
    for ($i = 1; $i <= $numChoices; $i++) {
        // 取得每個下拉選單的值
        $choice = isset($_POST['choice' . $i]) ? $_POST['choice' . $i] : '';

    
        // 檢查是否選擇了科系
        if (!empty($choice)) {
            // 這裡我們假設有一個 `user_choices` 表存儲使用者選擇的科系
            $sql = "INSERT INTO Department (school_id, department_name) VALUES (?, ?)"; // 假設 user_id = 1，根據實際情況修改
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $ID,$choice);
            $stmt->execute();
        }
    }
    echo "新增成功";
    header("Location: portfolio create-03(顯示科系).php?school_id=" . $school_id);
} else {
    echo "沒有選擇任何科系！";
}

$conn->close();
?>