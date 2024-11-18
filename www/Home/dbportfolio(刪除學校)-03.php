<?php
// 連接到資料庫
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱

$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 獲取要刪除的 school_id（假設是從 GET 請求獲取）
$school_id = $_GET['school_id'];

// 檢查是否設置了 school_id
if (isset($school_id)) {
    // 刪除資料
    $sql = "DELETE FROM School WHERE school_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $school_id);

    if ($stmt->execute()) {
        echo "學校資料及其相關科系已刪除成功";
        header("location:portfolio delete-03(編輯).php");
    } else {
        echo "錯誤: " . $conn->error;
        header("location:portfolio delete-03(編輯).php");
    }

    // 關閉語句和連接
    $stmt->close();
} else {
    echo "未指定要刪除的學校 ID";
}

$conn->close();
?>