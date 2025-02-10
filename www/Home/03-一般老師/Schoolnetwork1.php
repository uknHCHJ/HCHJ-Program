<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連接資料庫失敗: " . $conn->connect_error);
}

// SQL 查詢資料
$sql = "SELECT id, name, Public_Private, address, website FROM SecondSkill";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SecondSkill 資料顯示</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      height: 100vh;
      background-color: #f4f4f9;
    }

    .cards-container {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
      width: 80%;
      margin-top: 20px;
    }

    .card {
      background-color: white;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
    }

    .card h3 {
      margin: 0;
      font-size: 1.5em;
    }

    .card p {
      margin: 10px 0;
      color: #555;
    }

    .card a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>

<h1>SecondSkill 資料顯示</h1>
<div class="cards-container">
<?php
if ($result->num_rows > 0) {
    // 輸出每一行
    while($row = $result->fetch_assoc()) {
        echo "<div class='card'>";
        echo "<h3>" . $row['name'] . "</h3>";
        echo "<p><strong>公/私立:</strong> " . $row['Public_Private'] . "</p>";
        echo "<p><strong>地址:</strong> " . $row['address'] . "</p>";
        echo "<p><strong>網站:</strong> <a href='" . $row['website'] . "' target='_blank'>" . $row['website'] . "</a></p>";
        echo "</div>";
    }
} else {
    echo "沒有資料";
}
$conn->close();
?>
</div>

</body>
</html>
