<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 連接 MySQL 資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);

// 檢查連接是否成功
if (!$link) {
    // 輸出錯誤訊息並以 JSON 格式回應
    $response[0] = "無法連接資料庫：" . mysqli_connect_error();
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// 載入PHPWord庫
require_once '/~HCHJ/Home/PHPWord-master/path_to_phpword/autoload.php';

// MySQL連線設定
$host = 'localhost';
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

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
$phpWord->save($outputFile, 'Word2007');

echo "Word document created successfully: <a href='$outputFile'>$outputFile</a>";
?>