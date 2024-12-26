<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

// 檢查是否有提供檔案 ID
if (isset($_POST['file_id'])) {
  $file_id = intval($_POST['file_id']); // 取得並檢查檔案 ID

  // 從資料庫查詢檔案資訊
  $stmt = $link->prepare("SELECT name, type, data FROM file WHERE id = ?");
  if (!$stmt) {
    die("資料庫查詢準備失敗：" . $link->error);
  }
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