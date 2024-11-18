<?php
// 連接資料庫，使用準備式語句
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq"; // 建議使用環境變數或配置文件存儲
$dbname = "HCHJ";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn){
    mysqli_query($conn,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
}if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

// 取得圖片資料
$sql = "SELECT image_path FROM information";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['image']) . '"/>';
        echo "</div>";
    }
} else {
    echo "沒有圖片。";
}

$conn->close();
?>