<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 引入 PHPWord 的核心類和所有相關檔案
require_once '/data/HCHJ/www/Home/lib/PhpWord/PhpWord.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Settings.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Style.php';

// 引入集合類
require_once '/data/HCHJ/www/Home/lib/PhpWord/Collection/AbstractCollection.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Collection/Bookmarks.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Cell.php';
// 引入元素類
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/AbstractElement.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Text.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Table.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Row.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Cell.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Element/Image.php';

// 引入寫入器相關檔案
require_once '/data/HCHJ/www/Home/lib/PhpWord/Writer/WriterInterface.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Writer/AbstractWriter.php';
require_once '/data/HCHJ/www/Home/lib/PhpWord/Writer/Word2007.php';
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
