<?php
// 引入 PHPWord 所需檔案
require_once '/Home/PHPWord-master/src/PhpWord/PhpWord.php';

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
        $stmt = $pdo->query("SELECT name, img FROM history");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("競賽名稱: " . htmlspecialchars($row['name']));
            $section->addImageFromString($row['img'], ['width' => 200, 'height' => 150]);
        }
    }
    if ($option == 'all' || $option == 'competition') {
        $stmt = $pdo->query("SELECT name, image FROM Certificate");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("證照名稱: " . htmlspecialchars($row['name']));
            $section->addImageFromString($row['image'], ['width' => 200, 'height' => 150]);
        }
    }
}

// 保存文件並提供下載
header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="exported_file.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Cache-Control: must-revalidate');
header('Content-Length: ' . filesize($outputFile));

$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit();
?>
