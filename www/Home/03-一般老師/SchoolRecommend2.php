<?php
// 資料庫連接設置
$host = '127.0.0.1'; // 資料庫主機
$db = 'school_db';   // 資料庫名稱
$user = 'root';      // 資料庫用戶
$pass = '';          // 資料庫密碼

header('Content-Type: application/json');

try {
    // 使用 PDO 連接資料庫
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 查詢每所學校被選擇為各志願序的次數
    $sql = "
        SELECT school_name, priority, COUNT(*) AS count
        FROM choices
        GROUP BY school_name, priority
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    // 整理數據
    $data = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $school = $row['school_name'];
        $priority = $row['priority'];
        $count = $row['count'];

        if (!isset($data[$school])) {
            $data[$school] = [0, 0]; // 初始化第一志願和第二志願計數
        }
        $data[$school][$priority - 1] = $count;
    }

    echo json_encode($data);
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>