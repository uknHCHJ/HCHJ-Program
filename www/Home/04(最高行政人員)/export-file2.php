<?php
session_start();
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable; // 用於設定表格置中
use PhpOffice\PhpWord\Style\Section;
use PhpOffice\PhpWord\Style\TOC; // 用於設定目錄樣式

// 初始化 PhpWord 並設定全局預設字型與大小
$phpWord = new PhpWord();
$phpWord->setDefaultFontName('標楷體'); // 全文件預設使用標楷體
$phpWord->setDefaultFontSize(12);

// 註冊標題樣式供目錄使用（層級1）
$phpWord->addTitleStyle(1, ['bold' => true, 'size' => 25, 'color' => '333399'], ['alignment' => Jc::CENTER]);

// --------------------
// 1. 建立封面頁（不加頁碼）
// --------------------
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

// 封面標題
$coverSection->addText(
    '備審資料',
    ['bold' => true, 'size' => 36, 'color' => '333399'],
    ['alignment' => Jc::CENTER, 'spaceBefore' => 300, 'spaceAfter' => 300]
);
$coverSection->addTextBreak(5);

// 顯示使用者姓名（假設 Session 中有 user['name']）
$coverSection->addText(
    '姓名：' . htmlspecialchars($_SESSION['user']['name']),
    ['size' => 18],
    ['alignment' => Jc::CENTER, 'spaceBefore' => 200, 'spaceAfter' => 200]
);

// 生成日期並置於右下角
$date = date('Y-m-d');
$coverSection->addText(
    "生成日期：$date",
    ['size' => 12, 'italic' => true],
    ['alignment' => Jc::RIGHT, 'spaceBefore' => 5000]
);

// --------------------
// 1.5 新增目錄頁（不加頁碼）
// --------------------
$tocSection = $phpWord->addSection();
$tocSection->addText(
    "目錄",
    ['bold' => true, 'size' => 36, 'color' => '333399'],
    ['alignment' => Jc::CENTER, 'spaceAfter' => 300]
);
$tocSection->addTextBreak(1);
$tocSection->addTOC([
    'tabLeader'    => TOC::TAB_LEADER_DOT,
    'rightTabStop' => 9070
]);

// --------------------
// 2. 資料庫連線及相關設定
// --------------------
$servername = "127.0.0.1";
$username   = "HCHJ";
$password   = "xx435kKHq";
$dbname     = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);
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

// --------------------
// 定義查詢選項對應
// --------------------
$queryMap = [
    'competition'     => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '競賽證明'",
    'transcript'      => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '成績單'",
    // 自傳
    'autobiography'   => "SELECT file_name, file_content, autobiography_content FROM portfolio WHERE student_id = '$userId' AND category = '自傳'",
    'diploma'         => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '學歷證明'",
    'internship'      => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '實習證明'",
    'certifications'  => "", // 相關證照之後再處理
    'language'        => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '語言能力證明'",
    'other'           => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '其他資料'",
    'Proof-of-service'=> "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '服務證明'",
    // 讀書計畫：除了抓取 file_name 與 file_content，也抓取前端輸入的文字（autobiography_content）
    'read'            => "SELECT file_name, file_content, autobiography_content FROM portfolio WHERE student_id = '$userId' AND category = '讀書計畫'",
];

// --------------------
// 處理專業證照選項
// --------------------
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

// --------------------
// 定義中文選項標題
// --------------------
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

// --------------------
// 自傳專用的文字與段落樣式（讀書計畫也使用相同樣式）
// --------------------
$autobiographyTextStyle = ['name' => '標楷體', 'size' => 14, 'color' => '000000'];
$autobiographyParagraphStyle = ['alignment' => Jc::BOTH, 'spaceAfter' => 100];

// --------------------
// 4. 依使用者選取的順序處理各個匯出選項
// --------------------
foreach ($options as $option) {

    // ...（其他選項的處理如競賽證明、自傳、專業證照、等等）...

    // 自傳：根據資料庫中的檔案名稱判斷是否為 .docx
    if ($option === 'autobiography') {
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}', null, ['alignment' => Jc::CENTER]);
        $section->addTitle("自傳", 1);
        
        $sql = $queryMap['autobiography'];
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            // 假設自傳只會有一筆記錄，如有多筆可依需求處理
            while ($row = $result->fetch_assoc()) {
                $autobiographyFileName = $row['file_name'];
                $fileContent = $row['file_content'];
                $autobiography_content = $row['autobiography_content'];
                $ext = strtolower(pathinfo($autobiographyFileName, PATHINFO_EXTENSION));
                
                if ($ext === 'docx') {
                    try {
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
    
                            $section->addText($content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                        } else {
                            unlink($tempFilePath);
                            throw new Exception('無法開啟 .docx 文件');
                        }
                    } catch (Exception $e) {
                        $section->addText("讀取 DOCX 自傳檔案失敗： " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000'], $autobiographyParagraphStyle);
                    }
                } else {
                    $section->addText($autobiography_content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                }
            }
        } else {
            $section->addText("未提供自傳內容", $autobiographyTextStyle, $autobiographyParagraphStyle);
        }
        continue;
    }
    // 新增讀書計畫部分：版面樣式與自傳相同，並判斷是否為 .docx 檔案
    elseif ($option === 'read') {
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}', null, ['alignment' => Jc::CENTER]);
        $section->addTitle("讀書計畫", 1);
        
        $sql = $queryMap['read'];
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            // 假設讀書計畫只會有一筆記錄，如有多筆可依需求處理
            while ($row = $result->fetch_assoc()) {
                $readFileName = $row['file_name'];
                $fileContent = $row['file_content'];
                $read_content = $row['autobiography_content'];
                $ext = strtolower(pathinfo($readFileName, PATHINFO_EXTENSION));
                
                if ($ext === 'docx') {
                    try {
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
    
                            $section->addText($content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                        } else {
                            unlink($tempFilePath);
                            throw new Exception('無法開啟 .docx 文件');
                        }
                    } catch (Exception $e) {
                        $section->addText("讀取 DOCX 讀書計畫檔案失敗： " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000'], $autobiographyParagraphStyle);
                    }
                } else {
                    $section->addText($read_content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                }
            }
        } else {
            $section->addText("未提供讀書計畫內容", $autobiographyTextStyle, $autobiographyParagraphStyle);
        }
        continue;
    }
    // ...（其餘選項處理，如專題資料、專業證照、成績單、學歷證明、實習證明、語言能力證明等）...
    
    // 其他選項處理（原有邏輯）
    elseif (in_array($option, ['transcript', 'diploma', 'internship', 'language'])) {
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}', null, ['alignment' => Jc::CENTER]);
        $section->addTitle($optionNames[$option], 1);
    }
    else {
        $section = $phpWord->addSection();
        $footer = $section->addFooter();
        $footer->addPreserveText('{PAGE}', null, ['alignment' => Jc::CENTER]);
        if (isset($optionNames[$option])) {
            $section->addTitle($optionNames[$option], 1);
        }
    }
    $section->addTextBreak(1);
    
    // 其他選項的查詢與處理（依原有邏輯）...
    $sql = $queryMap[$option];
    if ($sql == "") {
        continue;
    }
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $description = $row['file_name'];
            $fileContent = $row['file_content'];
            $ext = strtolower(pathinfo($description, PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                if (in_array($option, ['transcript', 'diploma', 'internship', 'language'])) {
                    $textStyle = ['size' => 12];
                    $paragraphStyle = ['alignment' => Jc::CENTER];
                } elseif ($option === 'autobiography') {
                    $textStyle = $autobiographyTextStyle;
                    $paragraphStyle = $autobiographyParagraphStyle;
                } else {
                    $textStyle = ['size' => 12];
                    $paragraphStyle = ['alignment' => Jc::BOTH];
                }
                $section->addText("檔案名稱：$description", $textStyle, $paragraphStyle);
                if (!empty($fileContent) && strlen($fileContent) > 100) {
                    try {
                        $section->addImage($fileContent, [
                            'width'     => 300,
                            'height'    => 200,
                            'alignment' => Jc::CENTER,
                        ]);
                    } catch (Exception $e) {
                        $section->addText("插入圖片失敗：$description", ['italic' => true, 'color' => 'FF0000'], $paragraphStyle);
                    }
                } else {
                    $section->addText("無效的影像資料：$description", $textStyle, $paragraphStyle);
                }
                $section->addTextBreak(1);
            }
            else if ($ext === 'docx') {
                if (!empty($fileContent)) {
                    try {
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
                            if ($option === 'autobiography') {
                                $section->addText($content, $autobiographyTextStyle, $autobiographyParagraphStyle);
                            } elseif (in_array($option, ['transcript', 'diploma', 'internship', 'language'])) {
                                $section->addText($content, ['name' => 'Times New Roman', 'size' => 12], ['alignment' => Jc::CENTER]);
                            } else {
                                $section->addText($content, ['name' => 'Times New Roman', 'size' => 12], ['alignment' => Jc::BOTH]);
                            }
                        } else {
                            unlink($tempFilePath);
                            throw new Exception('無法開啟 .docx 文件');
                        }
                    } catch (Exception $e) {
                        $section->addText("讀取 DOCX 檔案失敗：$description - " . $e->getMessage(), ['italic' => true, 'color' => 'FF0000']);
                    }
                } else {
                    $section->addText("無效的 DOCX 檔案資料：$description");
                }
            }
            else {
                $section->addText("不支援的檔案類型：$description", ['italic' => true, 'color' => 'FF0000'], ['alignment' => Jc::CENTER]);
            }
            $section->addTextBreak(1);
        }
    } else {
        $section->addText("查無資料：{$optionNames[$option]}", ['size' => 12], ['alignment' => Jc::CENTER]);
    }
}

$conn->close();

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
