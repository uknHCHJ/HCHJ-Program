<?php
session_start();
include 'db.php';

// 驗證是否已登入
if (!isset($_SESSION['user'])) {
    echo ("<script>
                alert('請先登入！！');
                window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

// 從 SESSION 獲取用戶資料
$userData = $_SESSION['user'];
$userId = htmlspecialchars($userData['user'], ENT_QUOTES, 'UTF-8');

// 資料庫連線設置
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱

header('Content-Type: application/json'); // API 回傳 JSON 格式

try {
    // 使用 PDO 連接資料庫
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL 查詢：統計每所學校的選擇人數
    $sql = 'SELECT school_id, COUNT(*) AS count FROM Prefences GROUP BY school_id ORDER BY count DESC';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 整理查詢結果
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'school' => $row['school_id'],
            'count' => (int)$row['count']
        ];
    }

    // 回傳 JSON 數據
    echo json_encode($result);
} catch (PDOException $e) {
    error_log("Database Error: " . $e->getMessage()); // 記錄錯誤
    echo json_encode(["error" => "資料庫連接失敗，請聯繫管理員。"]);
}
?>
