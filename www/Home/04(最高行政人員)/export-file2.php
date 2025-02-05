<?php
session_start();
require_once 'vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Style\Section;

// 初始化 PhpWord 並設定全局預設字型與大小
$phpWord = new PhpWord();
$phpWord->setDefaultFontName('標楷體'); // 全文件預設使用標楷體
$phpWord->setDefaultFontSize(12);

// 添加封面頁，設定邊界
$section = $phpWord->addSection([
    'marginTop'    => 1000,
    'marginBottom' => 1000,
    'marginLeft'   => 1200,
    'marginRight'  => 1200,
    'borderBottomSize' => 12,
    'borderLeftSize'   => 12,
    'borderRightSize'  => 12,
    'borderTopSize'    => 12,
    'borderColor'      => '000000', // 黑色框線
]);
$coverPositionStyle = [
    'positioning'       => 'absolute',        // 絕對定位
    'posHorizontalRel'  => 'page',            // 以頁面為水平參照
    'posHorizontal'     => 'center',          // 水平置中
    'posVerticalRel'    => 'page',            // 以頁面為垂直參照
    'posVertical'       => 'center',          // 垂直置中
];


// 設定封面標題 (中文預設字型：標楷體)
$section->addText(
    '備審資料',
    ['bold' => true, 'size' => 36, 'color' => '333399'],
    ['alignment' => Jc::CENTER, 'spaceBefore' => 300, 'spaceAfter' => 300]
);
$section->addTextBreak(5);

// 顯示使用者姓名及學號 (中文預設字型：標楷體)
$section->addText(
    '姓名：' . htmlspecialchars($_SESSION['user']['name']),
    ['size' => 18],
    ['alignment' => Jc::CENTER, 'spaceBefore' => 200, 'spaceAfter' => 200]
);

// 生成日期並置於右下角的頁尾(只有封面)
$date = date('Y-m-d');
$section->addText(
    "生成日期：$date",
    ['size' => 12, 'italic' => true],
    ['alignment' => Jc::RIGHT, 'spaceBefore' => 5000]
);

// 添加分節符 (封面與內容分隔)
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
$userId = $userData['user'];
$username = $userData['name'];

// 檢查使用者選項
$options = isset($_POST['options']) ? $_POST['options'] : [];
if (empty($options)) {
    die("未選擇任何匯出選項！");
}

// 定義查詢選項對應
$queryMap = [
    'license'       => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '證照資料'",
    'competition'   => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '競賽證明'",
    'transcript'    => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '成績單'",
    'autobiography' => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '自傳'",
    'diploma'       => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '學歷證明'",
    'internship'    => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '實習證明'",
    'certifications'=> "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '相關證照'",
    'language'      => "SELECT file_name, file_content FROM portfolio WHERE student_id = '$userId' AND category = '語言能力證明'"
];

// 定義中文選項標題對應
$optionNames = [
    'license'       => '證照資料',
    'competition'   => '競賽證明',
    'transcript'    => '成績單',
    'autobiography' => '自傳',
    'diploma'       => '學歷證明',
    'internship'    => '實習證明',
    'certifications'=> '相關證照',
    'language'      => '語言能力證明'
];

// 添加匯出標題 (中文預設字型：標楷體)
$section->addText("備審資料匯出", ['bold' => true, 'size' => 25, 'color' => '333399'], ['alignment' => Jc::CENTER]);

// 根據使用者選擇的選項進行資料查詢與輸出
foreach ($options as $option) {
    if (!isset($queryMap[$option])) {
        $section->addText("未知的選項：$option");
        continue;
    }

    $sql = $queryMap[$option];
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        // 加入每個選項的中文標題
        if (isset($optionNames[$option])) {
            $section->addText($optionNames[$option], ['bold' => true, 'size' => 14, 'color' => '333399'], ['alignment' => Jc::LEFT]);
        }
        $section->addTextBreak(1);

        while ($row = $result->fetch_assoc()) {
            $description = $row['file_name'];
            $fileContent = $row['file_content'];

            // 取得檔案副檔名（轉小寫）
            $ext = strtolower(pathinfo($description, PATHINFO_EXTENSION));

            // 處理圖片檔案
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                // 使用中文說明（預設標楷體）
                $section->addText("檔案名稱：$description", ['size' => 12], ['alignment' => Jc::BOTH]);
                if (!empty($fileContent) && strlen($fileContent) > 100) {
                    try {
                        $section->addImage($fileContent, [
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
            }
            // 處理 DOCX 檔案
            else if ($ext === 'docx') {
                if (!empty($fileContent)) {
                    try {
                        // 建立一個臨時檔案，並將二進位資料寫入該檔案
                        $tempFilePath = tempnam(sys_get_temp_dir(), 'docx_');
                        if ($tempFilePath === false) {
                            throw new Exception("無法建立臨時檔案");
                        }
                        file_put_contents($tempFilePath, $fileContent);
            
                        // 使用 ZipArchive 開啟臨時檔案
                        $zip = new ZipArchive();
                        if ($zip->open($tempFilePath) === true) {
                            $xmlContent = $zip->getFromName('word/document.xml');
                            $zip->close();
            
                            // 刪除臨時檔案
                            unlink($tempFilePath);
            
                            if ($xmlContent === false) {
                                throw new Exception('未能讀取 document.xml 檔案');
                            }
            
                            // 解析 XML，抓取所有文字節點
                            $xml = simplexml_load_string($xmlContent);
                            $xml->registerXPathNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
                            $textNodes = $xml->xpath('//w:t');
            
                            $content = '';
                            foreach ($textNodes as $node) {
                                $content .= (string)$node . "\n";
                            }
                            // 若文件內容包含英文，可使用 Times New Roman 呈現英文內容
                            // 這裡假設所有文字均可能包含中英文，因此示範混合使用兩種字型
                            // 你可以根據實際需求將中英文分段處理
                            $section->addText(
                                $content,
                                ['name' => 'Times New Roman', 'size' => 12], // 英文內容使用 Times New Roman
                                ['alignment' => Jc::BOTH]
                            );
                        } else {
                            // 刪除臨時檔案
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
            // 其他不支援的檔案類型
            else {
                $section->addText("不支援的檔案類型：$description", ['italic' => true, 'color' => 'FF0000']);
            }
            $section->addTextBreak(1);
        }
    } else {
        $section->addText("查無資料：{$optionNames[$option]}");
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

// 儲存 Word 文件並輸出
try {
    $phpWord->save("php://output", 'Word2007');
} catch (Exception $e) {
    die("生成 Word 文件失敗：" . $e->getMessage());
}
?>
