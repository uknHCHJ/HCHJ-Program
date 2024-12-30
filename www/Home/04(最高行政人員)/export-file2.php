<?php

// 引入 PHPWord 所需檔案
require_once 'PHPWord-master/src/PhpWord/PhpWord.php';
require_once 'PHPWord-master/src/PhpWord/IOFactory.php';
require_once 'PHPWord-master/src/PhpWord/Writer/Word2007.php';

// 資料庫連線設定
$host = '127.0.0.1';
$dbname = 'HCHJ';
$username = 'HCHJ';
$password = 'xx435kKHq';
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("資料庫連線失敗：" . $e->getMessage());
}

// 取得 POST 資料
$options = isset($_POST['options']) ? $_POST['options'] : [];
if (empty($options)) {
    die("請選擇至少一個匯出選項！");
}

// 創建 Word 文件
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

// 查詢並生成內容
foreach ($options as $option) {
    if ($option == 'all' || $option == 'license') {
        $stmt = $pdo->query("SELECT img, name FROM history");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("競賽名稱: " . htmlspecialchars($row['name']));
            $section->addText("詳細資料: " . htmlspecialchars($row['img']));
        }
    }
    if ($option == 'all' || $option == 'competition') {
        $stmt = $pdo->query("SELECT name, image FROM Certificate");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("證照名稱: " . htmlspecialchars($row['name']));
            $section->addText("證照圖片: " . htmlspecialchars($row['image']));
        }
    }
}

// 保存文件
$outputFile = 'exported_file.docx';
try {
    $writer = new \PhpOffice\PhpWord\Writer\Word2007($phpWord);
    $writer->save($outputFile);
    echo "Word 文件已成功生成: <a href='$outputFile'>$outputFile</a>";
} catch (Exception $e) {
    die("生成文件時發生錯誤：" . $e->getMessage());
}
?>
