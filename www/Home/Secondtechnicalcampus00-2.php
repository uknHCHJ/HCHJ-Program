<?php
// 顯示錯誤訊息，便於調試
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 資料庫連線
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}

mysqli_set_charset($link, 'utf8');

// 確保使用者已登入
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 處理 CSV 上傳
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['csv_file'])) {
    $file_tmp = $_FILES['csv_file']['tmp_name'];

    if (($handle = fopen($file_tmp, 'r')) !== false) {
        $header = array_map('strtolower', array_map('trim', fgetcsv($handle)));

        if (!in_array('name', $header)) {
            die("CSV 檔案缺少 'name' 欄位。\n實際欄位: " . implode(", ", $header));
        }

        while (($data = fgetcsv($handle)) !== false) {
            $row = array_combine($header, $data);
            $name = trim($row['name'] ?? '');

            if ($name === '') {
                echo "跳過空的 name 欄位資料。<br>";
                continue;
            }

            $introduce = trim($row['introduce'] ?? '');
            $sql = sprintf(
                "INSERT INTO test (name, introduce) VALUES ('%s', '%s')",
                mysqli_real_escape_string($link, $name),
                mysqli_real_escape_string($link, $introduce)
            );

            if (!mysqli_query($link, $sql)) {
                echo "插入失敗: " . mysqli_error($link) . "<br>";
            } else {
                echo "成功插入: $name<br>";
            }
        }
        fclose($handle);
    } else {
        die("無法開啟上傳的 CSV 檔案。");
    }
}

mysqli_close($link);
?>
