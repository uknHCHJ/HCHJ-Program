<?php
// 連接到 MySQL 資料庫
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ"; // 請換成您的資料庫名稱

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 查詢資料庫中的比賽資料（不檢查結束時間）
$sql = "SELECT name, link FROM information";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>資管科比賽資訊</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 20px;
}

h1 {
    text-align: center;
    color: #333;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin: 20px auto; /* 使表格居中 */
}

th, td {
    padding: 12px;
    text-align: left;
    border: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f1f1f1;
}

a {
    color: #007BFF;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>

    <h1>資管科比賽資訊</h1>

    <?php
    // 如果有比賽資料
    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<thead><tr><th>比賽名稱</th><th>連結</th></tr></thead>';
        echo '<tbody>';

        // 顯示每個比賽的名稱和連結
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td><a href="' . htmlspecialchars($row['link']) . '" target="_blank">點擊參賽</a></td>';
            echo '</tr>';
        }

        echo '</tbody>';
        echo '</table>';
    } else {
        echo "<p>目前沒有任何比賽資訊。</p>";
    }

    // 關閉資料庫連接
    $conn->close();
    ?>

</body>
</html>
