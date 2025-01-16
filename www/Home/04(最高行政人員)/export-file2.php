<?php
session_start();
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

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

$userData = $_SESSION['user']; // 從 SESSION 中獲取使用者資料
$userId = $userData['user'];
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

// 添加標題
$section->addText("匯出資料如下：", ['bold' => true, 'size' => 16, 'color' => '333399'], ['alignment' => Jc::CENTER]);

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
            $imageData = $row['file_content'];

            // 處理文字資料
            $section->addText(!empty($description) ? $description : "無文字資料", ['size' => 12], ['alignment' => Jc::BOTH]);

            // 處理圖片資料
            if (!empty($imageData) && strlen($imageData) > 100) {
                $tempImagePath = __DIR__ . '/temp_images/' . uniqid('img_') . '.png';

                // 確保 temp_images 資料夾存在
                if (!is_dir(__DIR__ . '/temp_images')) {
                    mkdir(__DIR__ . '/temp_images', 0777, true);
                }

                if (file_put_contents($tempImagePath, $imageData)) {
                    try {
                        $section->addImage($tempImagePath, [
                            'width' => 300,
                            'height' => 200,
                            'alignment' => Jc::CENTER,
                        ]);
                    } catch (Exception $e) {
                        $section->addText("插入圖片失敗：$description", ['italic' => true, 'color' => 'FF0000']);
                    } finally {
                        unlink($tempImagePath); // 移除臨時圖片
                    }
                } else {
                    $section->addText("無法生成圖片檔案：$description");
                }
            } else {
                $section->addText("無效的影像資料：$description");
            }

            $section->addTextBreak(1); // 添加段落間距
        }
    } else {
        $section->addText("無 $option 資料可用");
    }
}

$conn->close();

// 設定下載標頭
header("Content-Description: File Transfer");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=\"exported_file.docx\"");
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
