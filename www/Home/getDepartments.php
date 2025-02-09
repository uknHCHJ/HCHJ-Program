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

// 接收學校 ID
$school_id = isset($_GET['school_id']) ? (int) $_GET['school_id'] : 0;

// 查詢 School_Department 表格，並根據傳入的 school_id 關聯 Secondskill 表格
$sql = "SELECT sd.department_id, sd.department_name
    FROM School_Department sd
    INNER JOIN Secondskill s ON sd.Secondskill_id = s.id
    WHERE s.id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $school_id);
$stmt->execute();

$result = $stmt->get_result();

$departments = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $departments[] = [
            'department_id' => $row['department_id'],
            'department_name' => $row['department_name']
        ];
    }
}

echo json_encode($departments);
$conn->close();
?>