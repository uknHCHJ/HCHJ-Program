<?php
// 資料庫連接設定
$servername = "127.0.0.1";
$username   = "HCHJ";
$password   = "xx435kKHq";
$dbname     = "HCHJ";

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    echo json_encode(["error" => "無法連接資料庫：" . mysqli_connect_error()], JSON_UNESCAPED_UNICODE);
    exit;
}
mysqli_query($link, "SET NAMES UTF8");

// 設定回應內容為 JSON
header('Content-Type: application/json');

// 檢查是否有傳遞有效的 class 參數
if (!isset($_GET['class']) || empty(trim($_GET['class']))) {
    echo json_encode(["error" => "請提供有效的 class 參數"], JSON_UNESCAPED_UNICODE);
    exit;
}

$selectedClass = trim($_GET['class']);
$selectedClass = mysqli_real_escape_string($link, $selectedClass);

/*
  假設資料庫中有兩個資料表：
  1. students：存放學生資料，欄位包含 student_id（學號）、name（姓名）、class（班級）；
  2. portfolio：存放學生繳交資料，至少有 student_id 欄位。
  若學生在 portfolio 有紀錄，則視為已繳交。
*/

// 查詢指定班級的所有學生，並利用 LEFT JOIN 判斷是否有繳交資料
$query = "SELECT s.student_id, s.name,
           CASE WHEN p.student_id IS NOT NULL THEN '已繳交' ELSE '未繳交' END AS submission_status
          FROM students s
          LEFT JOIN portfolio p ON s.student_id = p.student_id
          WHERE s.class = '$selectedClass'";

// 執行查詢
$result = mysqli_query($link, $query);
if (!$result) {
    echo json_encode(["error" => "查詢失敗：" . mysqli_error($link)], JSON_UNESCAPED_UNICODE);
    exit;
}

$students = array();
while ($row = mysqli_fetch_assoc($result)) {
    $students[] = $row;
}

echo json_encode($students, JSON_UNESCAPED_UNICODE);

mysqli_free_result($result);
mysqli_close($link);
?>
