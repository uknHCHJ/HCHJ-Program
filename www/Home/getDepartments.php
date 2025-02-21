<?php
session_start();

// 資料庫連線設定
$servername = "127.0.0.1";
$username   = "HCHJ";
$password   = "xx435kKHq";
$dbname     = "HCHJ";

// 確保 SESSION 中有儲存唯一識別使用者的資訊
if (!isset($_SESSION['user'])) {
    die(json_encode(["error" => "使用者未登入"]));
}

$userData = $_SESSION['user'];
$userId   = $userData['user'];

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 確認連線成功
if ($conn->connect_error) {
    die(json_encode(["error" => "連線失敗: " . $conn->connect_error]));
}

// 確保接收到 `school_id`
if (!isset($_GET['school_id']) || empty($_GET['school_id'])) {
    die(json_encode(["error" => "缺少 school_id"]));
}

$school_id = $_GET['school_id'];

// **使用 JOIN 來獲取該學校的所有科系**
$sql = "
    SELECT DISTINCT d.id AS department_id, d.department_name
    FROM test t
    JOIN Department d ON t.department_id = d.id
    WHERE t.school_id = ?
    ORDER BY d.department_name ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $school_id);
$stmt->execute();
$result = $stmt->get_result();

$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = $row;
    }
}

// 返回 JSON 給前端
echo json_encode($departments);

// 關閉連線
$stmt->close();
$conn->close();
?>
