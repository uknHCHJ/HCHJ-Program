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

$school_id = isset($_GET['school_id']) ? intval($_GET['school_id']) : 0;

// 如果沒有 school_id，返回學校列表頁面
if ($school_id == 0) {
    header("Location:portfolio-03(二技校園網介紹).php");
    exit;
}

// 從資料庫中取得該學校的科系
$sql = "SELECT department_name FROM departments WHERE school_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

// 獲取該學校名稱
$school_sql = "SELECT school_name FROM schools WHERE school_id = ?";
$school_stmt = $conn->prepare($school_sql);
$school_stmt->bind_param("i", $school_id);
$school_stmt->execute();
$school_result = $school_stmt->get_result();
$school = $school_result->fetch_assoc();

?>
