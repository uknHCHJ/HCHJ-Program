<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '/data/HCHJ/www/Home/lib/PhpWord/PhpWord.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Media.php'; // 手動引入 Media 類
require_once '/data/HCHJ/www/Home/lib/PhpWord/Style.php'; // 手動引入 Style 類
require_once '/data/HCHJ/www/Home/lib/PhpWord/Settings.php'; // 手動引入 Settings 類
require_once '/data/HCHJ/www/Home/lib/PhpWord/IOFactory.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/TemplateProcessor.php';

set_include_path(get_include_path() . PATH_SEPARATOR . '/data/HCHJ/www/Home/lib/PhpWord');

require_once 'PhpWord.php';
require_once 'Writer/WriterInterface.php';
require_once 'Writer/AbstractWriter.php';
require_once 'Writer/Word2007.php';
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
