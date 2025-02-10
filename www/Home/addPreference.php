<?php
session_start();
$servername = "127.0.0.1"; // 伺服器 IP 或本地端 localhost
$username = "HCHJ"; // 登入帳號
$password = "xx435kKHq"; // 密碼
$dbname = "HCHJ"; // 資料表名稱

// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => '未登入或會話過期']);
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user']; // 根據您實際的 session 結構獲取用戶 ID

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => '資料庫連線失敗: ' . $conn->connect_error]));
}


// 獲取 POST 資料
$data = json_decode(file_get_contents("php://input"), true);
if ($data === null) {
    echo json_encode(['success' => false, 'message' => '無法解析輸入的 JSON']);
    exit();
}

$sqldel="DELETE FROM Preferences WHERE user= $userId";
mysqli_query($conn, $sqldel);
// SQL 插入語句
$sql = "INSERT INTO preferences (student_user, Secondskill_id, school_name, department_id, department_name,preference_rank,created_at) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

$success = true; // 確保初始成功狀態為 true
$response = [];  // 確保初始回應為空

foreach ($data['preferences'] as $preference) {
    $Secondskill_id = $preference['Secondskill_id'];
    $school_name = $preference['school_name']; // 修正為 school_id
    $department_id = $preference['department_id']; // 修正為 department_id
    $department_name = $preference['department_name'];
    $preference_rank = $preference['preference_rank'];
    $created_at = $preference['created_at'];

    $stmt->bind_param("iiiiiii", $userId, $Secondskill_id ,$school_name, $department_id, $department_name,$preference_rank,$created_at);
    if (!$stmt->execute()) {
        $success = false;
        $response['message'] = $stmt->error;
        break;
    }
}

$stmt->close();
$conn->close();

$response['success'] = $success;
echo json_encode($response);

?>