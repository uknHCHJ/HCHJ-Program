<?php
// 連接資料庫
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱
//建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

//確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}

$sql = "SELECT id, department_name FROM department";
$result = $conn->query($sql);

// 生成下拉式選單
echo "<select>";
while($row = $result->fetch_assoc()) {
  echo "<option value='" . $row["id"] . "'>" . $row["department_name"] . "</option>";
}
echo "</select>";

$conn->close();
?>