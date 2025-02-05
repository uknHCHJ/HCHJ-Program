<?php
session_start();
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Border;
use PhpOffice\PhpWord\SimpleType\Jc;

// 初始化 PhpWord
$phpWord = new PhpWord();

// 新增封面 Section
$coverSection = $phpWord->addSection([
    'breakType' => 'nextPage', // 確保封面與正文分頁
    'borderColor' => '000000', // 外框顏色（黑色）
    'borderSize' => 12,        // 外框寬度（單位為 1/8 pt，12 為 1.5 pt）
]);

// 使用者名稱
$userData = $_SESSION['user'];
$username = isset($userData['name']) ? $userData['name'] : '未知使用者';

// 將使用者名稱置中，並加大字體
$coverSection->addTextBreak(10); // 增加空白行，讓內容居中靠下
$coverSection->addText(
    $username,
    ['bold' => true, 'size' => 36, 'color' => '333399'], // 樣式：加粗、大字體、顏色
    ['alignment' => Jc::CENTER] // 居中
);

// 添加更多空白行，讓 "備審資料" 位於稍下方
$coverSection->addTextBreak(3); 
$coverSection->addText(
    "備審資料",
    ['bold' => true,'size' => 24, 'color' => '666666'], // 樣式：字體大小與顏色
    ['alignment' => Jc::CENTER] // 居中
);

// 添加正文 Section
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

$userId = $userData['user'];
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

// 定義中文名稱對應
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

// 添加選取選項的標題
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
        if (isset($optionNames[$option])) {
            $section->addText($optionNames[$option], ['bold' => true, 'size' => 14, 'color' => '333399'], ['alignment' => Jc::LEFT]);
        }
        $section->addTextBreak(1);

        while ($row = $result->fetch_assoc()) {
            $description = $row['file_name'];
            $fileContent = $row['file_content'];
            $ext = strtolower(pathinfo($description, PATHINFO_EXTENSION));

            if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) {
                $section->addText("檔案名稱：$description", ['size' => 12], ['alignment' => Jc::BOTH]);
                try {
                    $section->addImage($fileContent, ['width' => 300, 'height' => 200, 'alignment' => Jc::CENTER]);
                } catch (Exception $e) {
                    $section->addText("插入圖片失敗：$description", ['italic' => true, 'color' => 'FF0000']);
                }
            } else {
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
