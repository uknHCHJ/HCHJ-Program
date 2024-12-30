<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 檢查是否有提供檔案 ID
if (isset($_POST['file_id'])) {
    $file_id = intval($_POST['file_id']); // 取得並檢查檔案 ID

    // 從資料庫查詢檔案資訊
    $stmt = $link->prepare("SELECT name, type, data FROM file WHERE id = ?");
    if (!$stmt) {
        die("資料庫查詢準備失敗：" . $link->error);
    }

    $stmt->bind_param("i", $file_id);
    $stmt->execute();
    $stmt->bind_result($name, $type, $data);
    if ($stmt->fetch()) {
        // 顯示檔案資訊
        header("Content-Type: $type");
        header("Content-Disposition: attachment; filename=$name");
        echo $data;
        exit;
    } else {
        echo "找不到檔案。";
        exit;
    }
}

// 獲取當前用戶的名稱和權限
$user = $_SESSION['user']['user'];

// 查詢用戶的權限
$stmt = $link->prepare("SELECT Permissions FROM user WHERE user = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($userPermissions);
$stmt->fetch();
$stmt->close();

$userPermissionsArray = explode(',', $userPermissions);

// 判斷用戶是否有學生或老師權限
$canViewStudentMessages = in_array('1', $userPermissionsArray); // 學生權限
$canViewTeacherMessages = in_array('2', $userPermissionsArray); // 老師權限

// 根據權限來篩選留言
if ($canViewStudentMessages && $canViewTeacherMessages) {
    // 如果用戶有學生和老師權限，顯示所有留言
    $query = "SELECT user, message FROM message ORDER BY id DESC";
} elseif ($canViewStudentMessages) {
    // 如果只有學生權限，顯示老師的留言
    $query = "SELECT user, message FROM message WHERE FIND_IN_SET('2', permissions) ORDER BY id DESC";
} elseif ($canViewTeacherMessages) {
    // 如果只有老師權限，顯示學生的留言
    $query = "SELECT user, message FROM message WHERE FIND_IN_SET('1', permissions) ORDER BY id DESC";
} else {
    // 如果沒有對應權限，則不顯示任何留言
    $query = "SELECT user, message FROM message WHERE 1=0";
}

// 執行查詢並抓取結果
$result = $link->query($query);
$comments = [];
while ($row = $result->fetch_assoc()) {
    $comments[] = $row;
}
$result->free();

// 顯示留言
foreach ($comments as $comment) {
    echo "<p><strong>{$comment['user']}</strong>: {$comment['message']}</p>";
}
?>
