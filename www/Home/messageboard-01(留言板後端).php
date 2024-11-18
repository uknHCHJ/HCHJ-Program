<?php
session_start();

// 確認使用者是否已登入
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// 資料庫連線
$servername = "127.0.0.1";
$dbUsername = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";

try {
    // 使用 PDO 連接資料庫
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $dbUsername, $dbPassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

// 獲取當前用戶的名稱和權限
$username = $_SESSION['user']['user']; 

// 查詢用戶的權限（可能是多個，用逗號分隔）
$stmt = $pdo->prepare("SELECT Permissions FROM user WHERE user = :user");
$stmt->execute([':user' => $username]);
$userPermissions = $stmt->fetchColumn(); // 獲取用戶的權限字串

// 分割權限字串為陣列
$userPermissionsArray = explode(',', $userPermissions);

// 判斷用戶是否有學生(1)或老師(2)的權限
$canViewStudentMessages = in_array('1', $userPermissionsArray);
$canViewTeacherMessages = in_array('2', $userPermissionsArray);

// 新增留言處理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = htmlspecialchars($_POST['message']); // 接收留言內容

    if (!empty($message)) {
        // 資料庫插入留言
        $stmt = $pdo->prepare("INSERT INTO message (user, message, permissions) VALUES (:user, :message, :permissions)");
        $stmt->execute([
            ':user' => $username,
            ':message' => $message,
            ':permissions' => $userPermissions // 儲存用戶的權限
        ]);
        // 重新導向避免重複提交表單
        header("Location: messageboard-01(留言板).php");
        exit();
    } else {
        echo "留言內容不能為空！";
    }
}

// 根據用戶權限篩選留言
if ($canViewStudentMessages && $canViewTeacherMessages) {
    // 如果用戶有學生和老師權限，顯示所有留言
    $stmt = $pdo->prepare("SELECT user, message FROM message ORDER BY id DESC");
} elseif ($canViewStudentMessages) {
    // 如果只有學生權限，顯示老師的留言
    $stmt = $pdo->prepare("SELECT user, message FROM message WHERE FIND_IN_SET('2', permissions) ORDER BY id DESC");
} elseif ($canViewTeacherMessages) {
    // 如果只有老師權限，顯示學生的留言
    $stmt = $pdo->prepare("SELECT user, message FROM message WHERE FIND_IN_SET('1', permissions) ORDER BY id DESC");
} else {
    // 如果沒有對應權限，則不顯示任何留言
    $stmt = $pdo->prepare("SELECT user, message FROM message WHERE 1=0");
}

$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>