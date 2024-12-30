<?php
// 手動加載 PHPWord 的必要文件
require_once 'PHPWord-master/src/PhpWord/PhpWord.php';
require_once 'PHPWord-master/src/PhpWord/IOFactory.php';


// MySQL連線設定
$host = '127.0.0.1';
$dbname = 'HCHJ';
$username = 'HCHJ';
$password = 'xx435kKHq';

// 建立MySQL連線
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 查詢圖片數據
$stmt = $pdo->query("SELECT image_name, image_data FROM photos");
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 創建Word文件
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

// 循環所有圖片並將其插入Word文檔
foreach ($images as $image) {
    $imageName = $image['image_name'];
    $imageData = $image['image_data'];

    // 將圖片保存為臨時文件
    $tempImagePath = tempnam(sys_get_temp_dir(), 'image_');
    file_put_contents($tempImagePath, $imageData);

    // 將圖片插入Word
    $section->addImage($tempImagePath, array('width' => 600, 'height' => 400));
    $section->addText($imageName);
}

// 保存Word文檔
$outputFile = 'exported_images.docx';
$writer = new \PhpOffice\PhpWord\Writer\Word2007($phpWord);
$writer->save($outputFile);

echo "Word document created successfully: <a href='$outputFile'>$outputFile</a>";
?>