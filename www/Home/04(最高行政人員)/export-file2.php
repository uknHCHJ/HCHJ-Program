<?php
session_start();
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use ZipArchive;

// 初始化 PhpWord
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 驗證資料庫連線
if ($conn->connect_error) {
    die("資料庫連線失敗：" . $conn->connect_error);
}
$userData = $_SESSION['user']; 
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username = $userData['name'];

// 檢查使用者選項
$options = isset($_POST['options']) ? $_POST['options'] : [];
if (empty($options)) {
    die("未選擇任何匯出選項！");
}

// 定義查詢選項對應
$queryMap = [
    'license' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '證照資料'",
    'competition' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '競賽證明'",
    'transcript' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '成績單'",
    'autobiography' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '自傳'",
    'diploma' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '學歷證明'",
    'internship' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '實習證明'",
    'certifications' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '相關證照'",
    'language' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '語言能力證明'"
];

// 定義中文名稱對應
$optionNames = [
    'license' => '證照資料',
    'competition' => '競賽證明',
    'transcript' => '成績單',
    'autobiography' => '自傳',
    'diploma' => '學歷證明',
    'internship' => '實習證明',
    'certifications' => '相關證照',
    'language' => '語言能力證明'
];

// 添加選取選項的標題
$section->addText("匯出選項：", ['bold' => true, 'size' => 16, 'color' => '333399'], ['alignment' => Jc::CENTER]);

// 根據選項生成文件內容
foreach ($options as $option) {
    if (!isset($queryMap[$option])) {
        $section->addText("未知的選項：$option");
        continue;
    }

    $sql = $queryMap[$option];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 加入每個選項的中文標題
        if (isset($optionNames[$option])) {
            $section->addText($optionNames[$option], ['bold' => true, 'size' => 14, 'color' => '333399'], ['alignment' => Jc::LEFT]);
        }
        $section->addTextBreak(1);

        while ($row = $result->fetch_assoc()) {
            $description = $row['file_name'];
            $fileContent = $row['file_content'];

            // 處理文字資料
            $section->addText(!empty($description) ? $description : "無文字資料", ['size' => 12], ['alignment' => Jc::BOTH]);

            // 處理圖片資料
            if (!empty($fileContent) && strlen($fileContent) > 100) {
                try {
                    // 直接插入二進制數據作為圖片
                    $section->addImage('data:image/jpeg;base64,' . base64_encode($fileContent), [
                        'width' => 300,
                        'height' => 200,
                        'alignment' => Jc::CENTER,
                    ]);
                } catch (Exception $e) {
                    $section->addText("插入圖片失敗：$description", ['italic' => true, 'color' => 'FF0000']);
                }
            } else {
                $section->addText("無效的影像資料：$description");
            }

            // 處理自傳檔案的二進制資料
            if ($option == 'autobiography' && !empty($fileContent)) {
                try {
                    // 使用 ZipArchive 來解析 .docx 文件
                    $zip = new ZipArchive();
                    $tempName = 'data://application/zip;base64,' . base64_encode($fileContent);

                    if ($zip->open($tempName) === true) {
                        // 解壓後讀取文檔中的文本內容
                        $xmlContent = $zip->getFromName('word/document.xml');
                        $zip->close();

                        // 使用 simplexml 解析 XML 內容
                        $xml = simplexml_load_string($xmlContent);
                        $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                        $textNodes = $xml->xpath('//w:t');

                        // 將自傳的文本內容插入到 Word 文件
                        $content = '';
                        foreach ($textNodes as $node) {
                            $content .= (string)$node . "\n";
                        }
                        $section->addText($content, ['size' => 12], ['alignment' => Jc::BOTH]);
                    } else {
                        throw new Exception('無法開啟 .docx 文件');
                    }
                } catch (Exception $e) {
                    die("讀取自傳檔案失敗：" . $e->getMessage());
                }
            }

            $section->addTextBreak(1); // 添加段落間距
        }
    }
}

$conn->close();

// 設定下載標頭
header("Content-Description: File Transfer");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=\"$userId.docx\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

// 儲存 Word 文件
try {
    $phpWord->save("php://output", 'Word2007'); 
} catch (Exception $e) {
    die("生成 Word 文件失敗：" . $e->getMessage());
}
?>
