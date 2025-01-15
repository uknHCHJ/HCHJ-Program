<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 連接資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error());
}

// 設置 UTF-8 編碼
mysqli_query($link, 'SET NAMES UTF8');

// 確保 SESSION 中有使用者資訊
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 取得資料表的欄位名稱
$tableColumns = [];
$result = mysqli_query($link, "DESCRIBE test");
while ($row = mysqli_fetch_assoc($result)) {
    $tableColumns[] = strtolower($row['Field']); // 將欄位名稱轉為小寫
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查是否有檔案上傳且無錯誤
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // 確認檔案格式為 CSV
        $filePath = $_FILES['file']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if ($fileExtension !== 'csv') {
            echo "請上傳 CSV 檔案！";
            exit;
        }

        // 開啟 CSV 檔案
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle);  // 讀取標題行
            if ($headers) {
                $headers = array_map('trim', $headers);  // 去除標題行的多餘空白
                $headers = array_map('strtolower', $headers); // 將標題轉為小寫
                $errorCount = 0;
                $lineNumber = 1;  // 記錄行號

                // 檢查標題行是否有對應到資料表欄位
                $validColumns = array_intersect($headers, $tableColumns);

                if (empty($validColumns)) {
                    echo "CSV 檔案的標題行與資料表欄位名稱無匹配，無法插入資料！";
                    fclose($handle);
                    exit;
                }

                // 讀取每一行資料
                while (($row = fgetcsv($handle)) !== FALSE) {
                    $lineNumber++;

                    // 準備資料
                    $data = [];
                    foreach ($headers as $index => $header) {
                        if (isset($row[$index]) && in_array($header, $validColumns)) {
                            $column = mysqli_real_escape_string($link, $header);
                            $value = mysqli_real_escape_string($link, trim($row[$index]));
                            $data["`$column`"] = "'$value'";
                        }
                    }

                    if (!empty($data)) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        $query = "INSERT INTO test ($columns) VALUES ($values)";
                        if (!mysqli_query($link, $query)) {
                            echo "第 " . $lineNumber . " 行資料庫錯誤: " . mysqli_error($link) . "<br>";
                        } else {
                            echo "第 " . $lineNumber . " 行資料成功寫入資料庫。<br>";
                        }
                    } else {
                        echo "第 " . $lineNumber . " 行資料無法映射到資料表的欄位，已跳過。<br>";
                        $errorCount++;
                    }
                }

                fclose($handle);

                if ($errorCount > 0) {
                    echo "共有 $errorCount 行資料無法處理，已跳過。<br>";
                } else {
                    echo "CSV 檔案資料已成功寫入資料庫。";
                }
            } else {
                echo "CSV 檔案格式錯誤，沒有標題行。";
            }
        } else {
            echo "無法打開 CSV 檔案。";
        }
    } else {
        echo "檔案上傳失敗，錯誤碼：" . $_FILES['file']['error'];
    }
}

mysqli_close($link);
?>
