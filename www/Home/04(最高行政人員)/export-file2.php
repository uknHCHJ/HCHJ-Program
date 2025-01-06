<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'lib/PhpWord/PhpWord.php';
use PhpOffice\PhpWord\PhpWord;

// 初始化 PhpWord
$phpWord = new PhpWord();

// 連接資料庫
$servername = "localhost";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}

// 查詢圖片數據
$sql = "SELECT name, img FROM history";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $section = $phpWord->addSection();

    while ($row = mysqli_fetch_assoc($result)) {
        $imageName = $row['name'];
        $imageData = $row['img'];

        // 將圖片寫入臨時文件
        $tempImage = tempnam(sys_get_temp_dir(), 'img_');
        file_put_contents($tempImage, $imageData);

        // 添加圖片和標題到 Word 文件
        $section->addText($imageName);
        $section->addImage($tempImage, array(
            'width' => 300,
            'height' => 200,
        ));

        // 刪除臨時文件
        unlink($tempImage);
    }

    // 將 Word 文件導出
    $fileName = "images_" . date('YmdHis') . ".docx";
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

    $tempFile = tempnam(sys_get_temp_dir(), 'word_');
    $phpWord->save($tempFile, 'Word2007');
    readfile($tempFile);
    unlink($tempFile);
} else {
    echo "沒有圖片數據。";
}

mysqli_close($conn);
?>
