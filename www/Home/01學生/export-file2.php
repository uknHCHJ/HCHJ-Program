<?php
session_start();
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\Style\TOC;

// --------------------------
// Helper Functions
// --------------------------

/**
 * 建立一個新的 section 並加上頁尾（包含頁碼）
 *
 * @param PhpWord $phpWord
 * @return \PhpOffice\PhpWord\Element\Section
 */
function createSectionWithFooter($phpWord) {
    $section = $phpWord->addSection();
    $footer = $section->addFooter();
    $footer->addPreserveText('{PAGE}', null, ['alignment' => Jc::CENTER]);
    return $section;
}

/**
 * 從 DOCX 檔案的二進位資料中提取文字內容
 *
 * @param string $fileContent
 * @return string
 * @throws Exception
 */
function extractDocxText($fileContent) {
    $tempFilePath = tempnam(sys_get_temp_dir(), 'docx_');
    if ($tempFilePath === false) {
        throw new Exception("無法建立臨時檔案");
    }
    file_put_contents($tempFilePath, $fileContent);
    $zip = new ZipArchive();
    if ($zip->open($tempFilePath) === true) {
        $xmlContent = $zip->getFromName('word/document.xml');
        $zip->close();
        unlink($tempFilePath);
        if ($xmlContent === false) {
            throw new Exception('未能讀取 document.xml 檔案');
        }
        $xml = simplexml_load_string($xmlContent);
        $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $textNodes = $xml->xpath('//w:t');
        $content = '';
        foreach ($textNodes as $node) {
            $content .= (string)$node . "\n";
        }
        return $content;
    } else {
        unlink($tempFilePath);
        throw new Exception('無法開啟 .docx 文件');
    }
}

/**
 * 根據檔案名稱副檔名，決定是加入圖片、讀取 DOCX 或輸出備用內容
 *
 * @param \PhpOffice\PhpWord\Element\Cell $cell
 * @param string $fileName
 * @param string $fileContent
 * @param string|null $fallbackContent 若非圖片或 DOCX，可傳入備用的文字內容
 * @param array $textStyle
 * @param array $paraStyle
 */
function addImageOrDocxContent($cell, $fileName, $fileContent, $fallbackContent = null, $textStyle = ['size' => 12], $paraStyle = ['alignment' => Jc::CENTER]) {
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
        try {
            $cell->addImage($fileContent, [
                'width'     => 300,
                'height'    => 200,
                'alignment' => Jc::CENTER,
            ]);
        } catch (Exception $e) {
            $cell->addText("圖片無法載入", ['color' => 'FF0000'], $paraStyle);
        }
        $cell->addText("檔案名稱：" . $fileName, $textStyle, $paraStyle);
    } else if ($ext === 'docx') {
        if (!empty($fileContent)) {
            try {
                $content = extractDocxText($fileContent);
                $cell->addText($content, $textStyle, $paraStyle);
            } catch (Exception $e) {
                $cell->addText("讀取 DOCX 檔案失敗：$fileName - " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000'], $paraStyle);
            }
        } else {
            $cell->addText("無效的 DOCX 檔案資料：$fileName", ['italic' => true, 'color' => 'FF0000'], $paraStyle);
        }
    } else {
        if ($fallbackContent !== null) {
            $cell->addText($fallbackContent, $textStyle, $paraStyle);
        } else {
            $cell->addText("不支援的檔案類型：$fileName", ['italic' => true, 'color' => 'FF0000'], $paraStyle);
        }
    }
}

// --------------------------
// 文件初始化與基本設定
// --------------------------

$phpWord = new PhpWord();
$phpWord->setDefaultFontName('標楷體');
$phpWord->setDefaultFontSize(12);
$phpWord->addTitleStyle(1, ['bold' => true, 'size' => 25, 'color' => '333399'], ['alignment' => Jc::CENTER]);

// 建立封面頁
$coverSection = $phpWord->addSection([
    'marginTop'       => 1000,
    'marginBottom'    => 1000,
    'marginLeft'      => 1200,
    'marginRight'     => 1200,
    'borderBottomSize'=> 12,
    'borderLeftSize'  => 12,
    'borderRightSize' => 12,
    'borderTopSize'   => 12,
    'borderColor'     => '000000',
]);
$coverSection->addText('備審資料', ['bold' => true, 'size' => 36, 'color' => '333399'], ['alignment' => Jc::CENTER, 'spaceBefore' => 300, 'spaceAfter' => 300]);
$coverSection->addTextBreak(5);
$coverSection->addText('姓名：' . htmlspecialchars($_SESSION['user']['name']), ['size' => 18], ['alignment' => Jc::CENTER, 'spaceBefore' => 200, 'spaceAfter' => 200]);
$date = date('Y-m-d');
$coverSection->addText("生成日期：$date", ['size' => 12, 'italic' => true], ['alignment' => Jc::RIGHT, 'spaceBefore' => 5000]);

// 建立目錄頁
$tocSection = $phpWord->addSection();
$tocSection->addText("目錄", ['bold' => true, 'size' => 36, 'color' => '333399'], ['alignment' => Jc::CENTER, 'spaceAfter' => 300]);
$tocSection->addTextBreak(1);
$tocSection->addTOC([
    'tabLeader'    => TOC::TAB_LEADER_DOT,
    'rightTabStop' => 9070
]);

// --------------------------
// 資料庫連線與查詢設定
// --------------------------

$servername = "127.0.0.1";
$usernameDB = "HCHJ";
$passwordDB = "xx435kKHq";
$dbname     = "HCHJ";

$conn = new mysqli($servername, $usernameDB, $passwordDB, $dbname);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}

$userData = $_SESSION['user'];
$userId   = $userData['user'];
$username = $userData['name'];

$options = isset($_POST['options']) ? $_POST['options'] : [];
if (empty($options)) {
    die("未選擇任何匯出選項！");
}
$autobiographyFile = isset($_POST['autobiography_file']) ? $_POST['autobiography_file'] : '';

// 定義各選項對應的查詢語法
$queryMap = [
    'competition'     => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '競賽證明'",
    'transcript'      => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '成績單'",
    'autobiography'   => "",
    'diploma'         => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '學歷證明'",
    'internship'      => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '實習證明'",
    'certifications'  => "",
    'language'        => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '語言能力證明'",
    'other'           => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '其他資料'",
    'Proof-of-service'=> "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '服務證明'",
    'read'            => "SELECT file_name, file_content, read_content FROM portfolio WHERE student_id = '$userId' AND category = '讀書計畫'"
];

// 處理專業證照查詢條件
if (!empty($_POST['certifications_files'])) {
    $selectedCertifications = $_POST['certifications_files'];
    $escapedCerts = array_map(function($cert) use ($conn) {
        return "'" . $conn->real_escape_string($cert) . "'";
    }, $selectedCertifications);
    $certInCondition = implode(',', $escapedCerts);
    if (!empty($certInCondition)) {
        $queryMap['certifications'] = "
            SELECT file_name, file_content, organization, certificate_name
            FROM portfolio
            WHERE student_id = '$userId'
              AND category = '專業證照'
              AND organization IN ($certInCondition)
        ";
    }
}

// 處理自傳查詢條件
if (!empty($_POST['autobiography_files'])) {
    $selectedAutobiographies = $_POST['autobiography_files'];
    $escapedAutobiographies = array_map(function($file) use ($conn) {
        return "'" . $conn->real_escape_string($file) . "'";
    }, $selectedAutobiographies);
    $autoInCondition = implode(',', $escapedAutobiographies);
    if (!empty($autoInCondition)) {
        $queryMap['autobiography'] = "
            SELECT file_name, file_content, autobiography_content
            FROM portfolio
            WHERE student_id = '$userId'
              AND category = '自傳'
              AND file_name IN ($autoInCondition)
        ";
    }
}

// 定義中文顯示名稱
$optionNames = [
    'competition'     => '競賽證明',
    'transcript'      => '成績單',
    'autobiography'   => '自傳',
    'diploma'         => '學歷證明',
    'internship'      => '實習證明',
    'certifications'  => '專業證照',
    'language'        => '語言能力證明',
    'other'           => '其他資料',
    'Proof-of-service'=> '服務證明',
    'read'            => '讀書計畫'
];

// 自傳與讀書計畫的文字樣式設定
$autobiographyTextStyle = ['name' => '標楷體', 'size' => 14, 'color' => '000000'];
$autobiographyParagraphStyle = ['alignment' => Jc::BOTH, 'spaceAfter' => 100];


foreach ($options as $option) {

    // 競賽證明：以表格方式，每頁顯示 3 筆資料
    if ($option === 'competition') {
        $result = $conn->query($queryMap['competition']);
        if ($result && $result->num_rows > 0) {
            $competitionData = $result->fetch_all(MYSQLI_ASSOC);
            $recordsPerPage = 3;
            $totalRecords = count($competitionData);
            for ($i = 0; $i < $totalRecords; $i += $recordsPerPage) {
                $section = createSectionWithFooter($phpWord);
                $section->addTitle($optionNames['competition'], 1);
                $tableStyle = [
                    'borderSize'  => 4,
                    'borderColor' => 'CCCCCC',
                    'cellMargin'  => 50,
                    'alignment'   => JcTable::CENTER
                ];
                $tableStyleName = 'CompetitionTable' . $i;
                $phpWord->addTableStyle($tableStyleName, $tableStyle);
                $table = $section->addTable($tableStyleName);
                $batch = array_slice($competitionData, $i, $recordsPerPage);
                foreach ($batch as $record) {
                    $table->addRow();
                    $cell = $table->addCell(9000);
                    try {
                        $cell->addImage($record['file_content'], [
                            'width'     => 300,
                            'height'    => 200,
                            'alignment' => Jc::CENTER,
                        ]);
                    } catch (Exception $e) {
                        $cell->addText("圖片無法載入", ['color' => 'FF0000'], ['alignment' => Jc::CENTER]);
                    }
                    $cell->addText("檔案名稱：" . $record['file_name'], ['size' => 12], ['alignment' => Jc::CENTER]);
                }
            }
        } else {
            $section = createSectionWithFooter($phpWord);
            $section->addTitle("查無資料：" . $optionNames['competition'], 1);
            $section->addText("查無資料：" . $optionNames['competition'], ['size' => 12], ['alignment' => Jc::CENTER]);
        }
        continue;
    }
    // 自傳：單獨自己一頁，然後需要判斷是否為文件(.docx)來下去做處理
    elseif ($option === 'autobiography') {
        $section = createSectionWithFooter($phpWord);
        $section->addTitle("自傳", 1);
        $result = $conn->query($queryMap['autobiography']);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fileName = $row['file_name'];
                $fileContent = $row['file_content'];
                $autobiographyContent = $row['autobiography_content'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($ext === 'docx') {
                    try {
                        $content = extractDocxText($fileContent);
                        $section->addText($content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                    } catch (Exception $e) {
                        $section->addText("讀取 DOCX 自傳檔案失敗： " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000'], $autobiographyParagraphStyle);
                    }
                } else {
                    $section->addText($autobiographyContent, $autobiographyTextStyle, $autobiographyParagraphStyle);
                }
            }
        } else {
            $section->addText("未提供自傳內容", $autobiographyTextStyle, $autobiographyParagraphStyle);
        }
        continue;
    }
    // 讀書計畫：版面與自傳相同
    elseif ($option === 'read') {
        $section = createSectionWithFooter($phpWord);
        $section->addTitle("讀書計畫", 1);
        $result = $conn->query($queryMap['read']);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $fileName = $row['file_name'];
                $fileContent = $row['file_content'];
                $readContent = $row['read_content'];
                $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                if ($ext === 'docx') {
                    try {
                        $content = extractDocxText($fileContent);
                        $section->addText($content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                    } catch (Exception $e) {
                        $section->addText("讀取 DOCX 讀書計畫檔案失敗： " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000'], $autobiographyParagraphStyle);
                    }
                } else {
                    $section->addText($readContent, $autobiographyTextStyle, $autobiographyParagraphStyle);
                }
            }
        } else {
            $section->addText("未提供讀書計畫內容", $autobiographyTextStyle, $autobiographyParagraphStyle);
        }
        continue;
    }
    // 專題資料：僅輸出一頁，顯示標題與提示文字
    elseif ($option === 'topics') {
        $section = createSectionWithFooter($phpWord);
        $section->addTitle("專題資料", 1);
        $footer = $section->addFooter();
        $footer->addText("此頁面僅供展示專題資料之用途。", ['size' => 14], ['alignment' => Jc::CENTER]);
        continue;
    }
    // 專業證照：每頁最多 6 筆資料（3 行 x 2 列）
    elseif ($option === 'certifications') {
        $resultCert = $conn->query($queryMap['certifications']);
        $certifications = $resultCert ? $resultCert->fetch_all(MYSQLI_ASSOC) : [];
        $maxPerPage = 6;
        $totalCerts = count($certifications);
        $pageIndex = 0;
        while ($pageIndex * $maxPerPage < $totalCerts) {
            $certSection = createSectionWithFooter($phpWord);
            $certSection->addTitle("專業證照", 1);
            $tableStyle = [
                'borderSize'  => 12,
                'borderColor' => '000000',
                'cellMargin'  => 50,
            ];
            $phpWord->addTableStyle('CertTable' . $pageIndex, $tableStyle);
            $table = $certSection->addTable('CertTable' . $pageIndex);
            $certsInPage = array_slice($certifications, $pageIndex * $maxPerPage, $maxPerPage);
            $cellCount = 0;
            for ($row = 0; $row < 3; $row++) {
                $table->addRow();
                for ($col = 0; $col < 2; $col++) {
                    $cell = $table->addCell(4500);
                    if (isset($certsInPage[$cellCount])) {
                        $cert = $certsInPage[$cellCount];
                        try {
                            $cell->addImage($cert['file_content'], [
                                'width'     => 198,
                                'height'    => 142,
                                'scaling'   => 100,
                                'alignment' => Jc::CENTER,
                            ]);
                        } catch (Exception $e) {
                            $cell->addText("圖片無法載入", ['color' => 'FF0000'], ['alignment' => Jc::CENTER]);
                        }
                        $cell->addText($cert['certificate_name'], ['size' => 12], ['alignment' => Jc::CENTER]);
                    }
                    $cellCount++;
                }
            }
            $pageIndex++;
        }
        continue;
    }
    // 其他選項（包含成績單、學歷證明、實習證明、語言能力證明、其他資料、服務證明、讀書計畫）
    // 和競賽證明用相同表格版面，每頁 3 筆資料
    elseif (in_array($option, ['transcript', 'diploma', 'internship', 'language', 'other', 'Proof-of-service', 'read'])) {
        $result = $conn->query($queryMap[$option]);
        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_all(MYSQLI_ASSOC);
            $recordsPerPage = 3;
            $totalRecords = count($data);
            for ($i = 0; $i < $totalRecords; $i += $recordsPerPage) {
                $section = createSectionWithFooter($phpWord);
                $section->addTitle($optionNames[$option], 1);
                $tableStyle = [
                    'borderSize'  => 4,
                    'borderColor' => 'CCCCCC',
                    'cellMargin'  => 50,
                    'alignment'   => JcTable::CENTER
                ];
                $tableStyleName = $option . 'Table' . $i;
                $phpWord->addTableStyle($tableStyleName, $tableStyle);
                $table = $section->addTable($tableStyleName);
                $batch = array_slice($data, $i, $recordsPerPage);
                foreach ($batch as $record) {
                    $table->addRow();
                    $cell = $table->addCell(9000);
                    $fileName = $record['file_name'];
                    $fileContent = $record['file_content'];
                    addImageOrDocxContent($cell, $fileName, $fileContent);
                }
            }
        } else {
            $section = createSectionWithFooter($phpWord);
            $section->addText("查無資料：" . $optionNames[$option], ['size' => 12], ['alignment' => Jc::CENTER]);
        }
        continue;
    }
    // 其他選項輸出Word
    else {
        $section = createSectionWithFooter($phpWord);
        if (isset($optionNames[$option])) {
            $section->addTitle($optionNames[$option], 1);
        }
        $section->addTextBreak(1);
        $sql = $queryMap[$option];
        if ($sql == "") continue;
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $section->addText("檔案名稱：" . $row['file_name'], ['size' => 12], ['alignment' => Jc::CENTER]);
            }
        } else {
            $section->addText("查無資料：{$optionNames[$option]}", ['size' => 12], ['alignment' => Jc::CENTER]);
        }
    }
}

$conn->close();

// --------------------------
// 輸出Word
// --------------------------
header("Content-Description: File Transfer");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=\"$userId.docx\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

try {
    $phpWord->save("php://output", 'Word2007');
} catch (Exception $e) {
    die("生成 Word 文件失敗：" . $e->getMessage());
}
?>
