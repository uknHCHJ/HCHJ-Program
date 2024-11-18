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
//echo "連線成功";
function choose()
{
    global $conn;
    $sql = "SELECT * FROM `volunteer`";
    $result = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row['ID'] . "'>" . $row['school_name'] . "</option>";
    }
}
function department($ID)
{
    //header('Content-Type: application/json');
    global $conn;
    $sql = "SELECT department.ID, department.department_name
            FROM assing
            INNER JOIN department ON assing.did = department.ID
            WHERE assing.school_ID = ?";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_array($result)) {
        echo "<option value='" . $row['ID'] . "'>" . $row['department_name'] . "</option>";
    }
    // 準備 SQL 語句
    $stmt = mysqli_prepare($conn, $sql);

    // 綁定參數
    mysqli_stmt_bind_param($stmt, "i",$school_ID);

    // 執行查詢
    mysqli_stmt_execute($stmt);

    // 獲取查詢結果
    $result = mysqli_stmt_get_result($stmt);

    $departments = [];

    // 取出每一行資料
    while ($row = mysqli_fetch_assoc($result)) {
        $departments[] = $row;
    }

    // 關閉語句
    mysqli_stmt_close($stmt);

    // 返回結果
    return $departments;
}

// 接收 AJAX 發送的請求
if (isset($_POST['id'])) {
    $school_ID = $_POST['id'];

    // 使用 department 函式取得對應科系
    $departments = department($school_ID);

    // 返回 JSON 格式的結果
    echo json_encode($department);
}


// 關閉連接
mysqli_close($link);
?> 