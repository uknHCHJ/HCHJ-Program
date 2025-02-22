<?php
require 'vendor/autoload.php'; // 引入 Composer 自動載入
use Smalot\PdfParser\Parser;

session_start();

// 確認使用者已登入
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}
 // 上傳表單
 echo '<form action="" method="post" enctype="multipart/form-data">';
 echo '選擇 PDF 檔案：<input type="file" name="pdf_file" required>';
 echo '<input type="submit" value="上傳並掃描">';
 echo '</form>';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['pdf_file'])) {
    // 上傳檔案（注意：此處內部仍使用暫存目錄處理，結果不會將路徑資訊顯示給使用者）
    $uploadDir = 'uploads/';
    $fileName = basename($_FILES['pdf_file']['name']);
    $uploadFile = $uploadDir . $fileName;

    // 檢查暫存檔案是否存在
    if (!is_uploaded_file($_FILES['pdf_file']['tmp_name'])) {
        die("暫存檔案不存在！");
    }

    // 嘗試移動檔案
    if (!move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadFile)) {
        error_log("檔案移動失敗：來源 " . $_FILES['pdf_file']['tmp_name'] . "，目標 " . $uploadFile);
        die("檔案上傳失敗！");
    }


    // 解析 PDF 檔案
    $parser = new Parser();
    $pdf = $parser->parseFile($uploadFile);
    $text = $pdf->getText();

    // 尋找「附錄十二」的起始位置
    $startPos = strpos($text, '附錄十二');
    if ($startPos === false) {
        die("找不到附錄十二的內容");
    }

    // 尋找「附錄十三」的起始位置
    $endPos = strpos($text, '附錄十三', $startPos);
    if ($endPos === false) {
        // 如果找不到附錄十三，就從附錄十二一直取到結尾
        $extractedText = substr($text, $startPos);
    } else {
        // 只取從附錄十二到附錄十三之前的內容
        $extractedText = substr($text, $startPos, $endPos - $startPos);
    }


    // 此正則假設各欄位以至少兩個空白分隔（依實際格式調整）
    foreach ($lines as $line) {
        if (preg_match('/^\s*(\S+.*?)\s{2,}(\S+.*?)\s{2,}(\S+.*?)\s{2,}(\S+.*?)\s*$/u', $line, $matches)) {
            $data[] = [
                '學校名稱' => trim($matches[1]),
                '科系(組)' => trim($matches[2]),
                '繳交資料' => trim($matches[3]),
                '指定項目甄審說明' => trim($matches[4])
            ];
        }
    }

    // 顯示掃描結果
    echo "<h2>掃描結果</h2>";
    if (!empty($data)) {
        echo "<table border='1' cellpadding='8' cellspacing='0'>";
        echo "<tr><th>學校名稱</th><th>科系(組)</th><th>繳交資料</th><th>指定項目甄審說明</th></tr>";
        foreach ($data as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['學校名稱'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['科系(組)'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['繳交資料'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "<td>" . htmlspecialchars($row['指定項目甄審說明'], ENT_QUOTES, 'UTF-8') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "沒有找到符合的資料。";
    }
} 
?>