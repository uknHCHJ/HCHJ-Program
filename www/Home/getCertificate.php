<?php
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
$sql = "SELECT school_id, school_name FROM School"; //學校

$result = $conn->query($sql);

$schools = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
}
echo json_encode($schools);
$conn->close();
?>