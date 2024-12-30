<?php
require_once 'PHPWord-master/src/PhpWord/PhpWord.php';
require_once 'PHPWord-master/src/PhpWord/IOFactory.php';
require_once 'PHPWord-master/src/PhpWord/Writer/Word2007.php';

$host = '127.0.0.1';
$dbname = 'HCHJ';
$username = 'HCHJ';
$password = 'xx435kKHq';

$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$options = isset($_POST['options']) ? $_POST['options'] : [];
if (empty($options)) {
    die("請選擇至少一個匯出選項！");
}

// 創建Word文件
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

foreach ($options as $option) {
    if ($option == 'all' || $option == 'license') {
        $stmt = $pdo->query("SELECT img, name FROM history");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("競賽名稱: " . $row['name']);
            $section->addText("詳細資料: " . $row['img']);
        }
    }
    if ($option == 'all' || $option == 'competition') {
        $stmt = $pdo->query("SELECT name, image FROM Certificate");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $section->addText("證照名稱: " . $row['name']);
            $section->addText("證照圖片: " . $row['image']);
        }
    }
}

$outputFile = 'exported_file.docx';
$writer = new \PhpOffice\PhpWord\Writer\Word2007($phpWord);
$writer->save($outputFile);

echo "Word 文件已成功生成: <a href='$outputFile'>$outputFile</a>";
?>
