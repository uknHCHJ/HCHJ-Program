<?php
// 連接資料庫
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['category'])) {
    $category = $_GET['category'];

    // 查詢對應分類的證照
    $sql = "SELECT id, name FROM certifications WHERE organization = '$category'";
    $result = $conn->query($sql);

    $certifications = [];
    while ($row = $result->fetch_assoc()) {
        $certifications[] = $row;
    }

    // 返回 JSON 格式
    echo json_encode($certifications);
}

$conn->close();
?>
