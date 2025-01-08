<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'vendor/autoload.php';

// 檢查使用者是否登入
if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user'];
$userName = $userData['name'];
$userId = $userData['user'];

// 資料庫連線設定
$host = '127.0.0.1';
$dbUser = 'HCHJ';
$dbPass = 'xx435kKHq';
$dbName = 'HCHJ';

$conn = mysqli_connect($host, $dbUser, $dbPass, $dbName);
if (!$conn) {
    die("資料庫連線失敗：" . mysqli_connect_error());
}

// 查詢相片資料
$sql = "SELECT img, name FROM history";
$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) == 0) {
    die("沒有資料可供匯出！");
}

// 建立 PHPWord 文件
$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

// 插入標題
$section->addText("相片匯出", ['bold' => true, 'size' => 20]);

// 循環插入相片與描述
while ($row = mysqli_fetch_assoc($result)) {
    $imageData = $row['img'];
    $description = $row['name'];

    // 檢查影像資料有效性
    if (empty($imageData) || strlen($imageData) < 100) {
        $section->addText("圖片資料無效：$description", ['italic' => true, 'color' => 'FF0000']);
        continue;
    }

    // 嘗試寫入圖片資料為臨時檔案
    $tempImagePath = tempnam(sys_get_temp_dir(), 'img') . '.png';
    file_put_contents($tempImagePath, $imageData);  // 將二進位資料寫入臨時檔案

    // 確認圖片檔案是否成功創建
    if (!file_exists($tempImagePath)) {
        $section->addText("無法創建圖片檔案：$description", ['italic' => true, 'color' => 'FF0000']);
        continue;
    }

    // 插入圖片（這裡指定圖片檔案路徑而非 URL）
    try {
        $section->addImage($tempImagePath, [
            'width' => 300,
            'height' => 200,
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            'inline' => true, // 確保圖片內嵌
        ]);
    } catch (Exception $e) {
        $section->addText("插入影像失敗：$description", ['italic' => true, 'color' => 'FF0000']);
    }

    // 刪除臨時圖片檔案
    unlink($tempImagePath);

    // 插入描述
    if (!empty($description)) {
        $section->addText($description);
    }
}

// 設定檔名與下載
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="photos.docx"');
header('Cache-Control: max-age=0');

$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');

mysqli_close($conn);
?>
