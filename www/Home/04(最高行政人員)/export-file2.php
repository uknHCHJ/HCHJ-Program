<?php
ini_set('max_execution_time', 300); // 允許腳本執行 300 秒

// 引入 PHPWord 所需檔案
require_once 'Home/PHPWord-master/src/PhpWord/PhpWord.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("請使用正確的表單提交！");
}
print_r($_POST); // 調試 POST 資料
exit();

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();
$section->addText('Hello World!');

header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="test.docx"');

$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');
exit();
?>

?>
