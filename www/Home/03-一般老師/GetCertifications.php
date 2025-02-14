<?php
header("Content-Type: application/json");

// 資料庫連線資訊
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(["error" => "連接資料庫失敗: " . $conn->connect_error]));
}

// 確保接收到 organization 參數
if (isset($_GET["organization"])) {
    $organization = $_GET["organization"];

    // 準備 SQL 查詢
    $stmt = $conn->prepare("SELECT id, name FROM certifications WHERE organization = ?");
    $stmt->bind_param("s", $organization);
    $stmt->execute();
    $result = $stmt->get_result();

    // 取得查詢結果
    $certifications = [];
    while ($row = $result->fetch_assoc()) {
        $certifications[] = $row;
    }

    // 返回 JSON
    echo json_encode($certifications);

    // 關閉資源
    $stmt->close();
} else {
    echo json_encode(["error" => "缺少必要參數"]);
}

$conn->close();
?>
