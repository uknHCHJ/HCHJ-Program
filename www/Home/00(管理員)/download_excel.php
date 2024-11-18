<?php


$file = 'download/sample.xlsx'; // 檔案路徑檢查

// 確認檔案路徑
$realPath = realpath($file);
if ($realPath === false || !file_exists($realPath)) {
    echo "檔案不存在: " . $file;
    exit();
}

// 設定 Headers 來強制下載
header('Content-Description: File Transfer');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.basename($realPath).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($realPath));

// 讀取並輸出檔案內容
readfile($realPath);
exit();
?>
