<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.html");
    exit();
}

$userData = $_SESSION['user']; // 從 SESSION 獲取用戶資料
$userId = htmlspecialchars($userData['user'], ENT_QUOTES, 'UTF-8'); // 確保數據安全

$query = sprintf("SELECT * FROM user WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);

if (!isset($_SESSION['user'])) {
    echo ("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}

header('Content-Type: application/json');

header('Content-Type: application/json'); // API 回傳 JSON 格式

try {
    // 使用 PDO 連接資料庫
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL 查詢：統計每所學校的選擇人數
    $sql = "
        SELECT school_name, COUNT(*) AS count
        FROM choices
        GROUP BY school_name
        ORDER BY count DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 整理查詢結果
    $result = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $result[] = [
            'school' => $row['school_name'],
            'count' => (int)$row['count']
        ];
    }

    // 回傳 JSON 數據
    echo json_encode($result);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>
