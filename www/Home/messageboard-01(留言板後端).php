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
$user = $_SESSION['user']['user']; 

// 查詢用戶的權限（可能是多個，用逗號分隔）
$stmt = $pdo->prepare("SELECT Permissions FROM user WHERE user = :user");
$stmt->execute([':user' => $user]);
$userPermissions = $stmt->fetchColumn(); // 獲取用戶的權限字串

// 假設用戶的權限已經從資料庫查詢並存儲在 $userPermissionsArray 中
$userPermissionsArray = explode(',', $userPermissions);

// 判斷用戶是否有學生或老師權限
$canViewStudentMessages = in_array('1', $userPermissionsArray); // 學生權限
$canViewTeacherMessages = in_array('2', $userPermissionsArray); // 老師權限

// 根據權限來篩選留言
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

// 執行查詢並抓取結果
$stmt->execute();
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>