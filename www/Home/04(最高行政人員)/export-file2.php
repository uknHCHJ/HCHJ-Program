<?php
require_once 'vendor/autoload.php';
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\SimpleType\Jc;

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

// 檢查資料表資料
$sql = "SELECT name, img FROM history";
$result = $conn->query($sql);

// 檢查資料是否正確返回
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // 顯示文字資料
        $description = $row['name'];
        if (!empty($description)) {
            $section->addText($description);
        } else {
            $section->addText("無文字資料");
        }

        // 處理圖片資料
        $imageData = $row['img'];
        if (empty($imageData) || strlen($imageData) < 100) {
            $section->addText("無效的影像資料：$description");
            continue;
        }

        $tempImagePath = tempnam(sys_get_temp_dir(), 'img') . '.png';
        if (!file_put_contents($tempImagePath, $imageData)) {
            $section->addText("無法生成臨時圖片檔案：$description");
            continue;
        }

        // 插入圖片
        try {
            if (file_exists($tempImagePath) && getimagesize($tempImagePath)) {
                $section->addImage($tempImagePath, [
                    'width' => 300,
                    'height' => 200,
                    'alignment' => Jc::CENTER,
                ]);
            } else {
                $section->addText("生成的圖片無效：$description");
            }
        } catch (Exception $e) {
            $section->addText("插入影像失敗：$description", ['italic' => true, 'color' => 'FF0000']);
            error_log("插入圖片失敗：" . $e->getMessage());
        } finally {
            if (file_exists($tempImagePath)) {
                unlink($tempImagePath);
            }
        }
    }
} else {
    $section->addText("無圖片資料可用");
}

$conn->close();

// 設定標頭以觸發下載
header("Content-Description: File Transfer");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Content-Disposition: attachment; filename=\"exported_file.docx\"");
header("Expires: 0");
header("Cache-Control: must-revalidate");
header("Pragma: public");

// 儲存 Word 檔案並輸出到使用者
try {
    $phpWord->save("php://output", 'Word2007');
} catch (Exception $e) {
    die("生成 Word 文件失敗：" . $e->getMessage());
}
?>
