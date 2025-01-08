<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;

$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addText("這是一個測試！");

header("Content-Description: File Transfer");
header('Content-Disposition: attachment; filename="test.docx"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$tempFile = tempnam(sys_get_temp_dir(), 'word_');
$phpWord->save($tempFile, 'Word2007');
readfile($tempFile);
unlink($tempFile);
?>
