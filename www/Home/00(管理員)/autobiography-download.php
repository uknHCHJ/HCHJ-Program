<?php
session_start();
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

if (!isset($_SESSION['user'])) {
    echo "<script>alert('請先登入！！'); window.location.href = '/~HCHJ/index.html';</script>";
    exit();
}

if (!isset($_GET['id'])) {
    die("缺少必要參數！");
}

$student_id = mysqli_real_escape_string($link, $_GET['id']);
$query = "SELECT file_content FROM portfolio WHERE student_id = ? AND category = '自傳'";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $student_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    die("沒有找到檔案！");
}

// 建立臨時 ZIP 檔案
$zip = new ZipArchive();
$zip_filename = tempnam(sys_get_temp_dir(), "zip_");
if ($zip->open($zip_filename, ZipArchive::CREATE) !== TRUE) {
    die("無法建立 ZIP 檔案！");
}

// 依序加入圖片，確保原始二進制不變
$index = 1;
while ($row = mysqli_fetch_assoc($result)) {
    $file_data = $row['file_content']; // 確保是 BLOB
    $file_name = $student_id . $index . ".doc"; 
    $zip->addFromString($file_name, $file_data);
    $index++;
}

$zip->close();
mysqli_stmt_close($stmt);
mysqli_close($link);

// 下載 ZIP 檔案
header("Content-Type: application/zip");
header("Content-Disposition: attachment; filename=student_{$student_id}_files.zip");
header("Content-Length: " . filesize($zip_filename));
readfile($zip_filename);

// 刪除臨時 ZIP
unlink($zip_filename);
exit();
?>
