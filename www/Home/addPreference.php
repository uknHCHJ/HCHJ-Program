<?php
// 開啟 session
session_start();

// 資料庫連線設定
$servername = "127.0.0.1"; // 伺服器 IP 或 localhost
$username   = "HCHJ";       // 資料庫帳號
$password   = "xx435kKHq";  // 密碼
$dbname     = "HCHJ";       // 資料庫名稱

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查資料庫連線是否成功
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "資料庫連線失敗: " . $conn->connect_error]));
}

// 檢查是否從前端傳來資料
$data = json_decode(file_get_contents("php://input"), true);

// 檢查 JSON 格式
if (!isset($data["preferences"]) || !is_array($data["preferences"])) {
    echo json_encode(["success" => false, "message" => "缺少 Preferences"]);
    exit;
}

// 檢查用戶是否已登入並從 session 獲取 student_user
if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => "學生未登入或未提供有效的 user_id"]);
    exit;
}

// 假設 'user' 是一個陣列，並且 'user_id' 是其中的某個鍵
$studentUser = $_SESSION['user']['user'] ?? null;  // 假設 session 中包含 'user' 這個欄位，並且 'user_id' 在其中

// 檢查是否獲取到有效的 student_user
if (!$studentUser) {
    echo json_encode(["success" => false, "message" => "學生未登入或 user_id 無效"]);
    exit;
}

// 準備 SQL 插入語句，包含學校名稱、科系名稱和學生的 ID (student_user)
$stmt = $conn->prepare("INSERT INTO Preferences (school_name, department_name, student_user) VALUES (?, ?, ?)");

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "SQL 查詢準備失敗: " . $conn->error]);
    exit;
}

// 循環處理每一個志願資料並插入到資料庫
foreach ($data["preferences"] as $pref) {
    if (!isset($pref["school_name"]) || !isset($pref["department_name"])) {
        echo json_encode(["success" => false, "message" => "缺少 school_name 或 department_name"]);
        exit;
    }

    // 綁定學校名稱、科系名稱和學生 ID
    $stmt->bind_param("sss", $pref["school_name"], $pref["department_name"], $studentUser);

    // 執行 SQL 插入操作
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "SQL 執行錯誤: " . $stmt->error]);
        exit;
    }
}

// 確保有成功影響資料庫
if ($stmt->affected_rows > 0) {
    echo json_encode(["success" => true, "message" => "資料已成功存入"]);
} else {
    echo json_encode(["success" => false, "message" => "SQL 執行失敗"]);
}

// 關閉語句
$stmt->close();

// 關閉資料庫連線
$conn->close();
?>
