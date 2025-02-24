<?php
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT * FROM information WHERE name LIKE '%$keyword%' LIKE '%$keyword%'";
    $result = $conn->query($sql);
} else {
    echo "請返回並輸入關鍵字來搜尋。";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>搜尋結果</title>
</head>
<body>
    <h1>搜尋結果</h1>

    <?php if ($result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div style="border:1px solid #ddd; padding: 10px; margin: 10px 0;">
                <h3><?= htmlspecialchars($row['name']) ?></h3>
                <a href="<?= htmlspecialchars($row['link']) ?>" target="_blank">查看詳細資料</a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>找不到相關結果。</p>
    <?php endif; ?>

    <!-- 返回搜尋頁面的按鈕 -->
    <div style="margin-top: 20px;">
        <a href="Contestsearch1.php"><button style="padding: 10px 15px; font-size: 16px;" >返回搜尋頁面</button></a>
    </div>
</body>
</html>