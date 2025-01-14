<?php
session_start();

// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 連接資料庫
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

// 設置 UTF-8 編碼
mysqli_query($link, 'SET NAMES UTF8');

// 確保 SESSION 中有使用者資訊
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 從 SESSION 中取得使用者資訊
$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

// 取得資料表欄位名稱
$tableColumns = [];
$result = mysqli_query($link, "DESCRIBE test");
while ($row = mysqli_fetch_assoc($result)) {
    $tableColumns[] = $row['Field'];
}

// 開啟 MySQL 錯誤報告（除錯用）
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        // 讀取檔案內容
        $fileName = $_FILES['file']['name'];
        $fileContent = file_get_contents($_FILES['file']['tmp_name']);

        // 去除 BOM 標記（如果存在）
        $fileContent = preg_replace('/^\xEF\xBB\xBF/', '', $fileContent);

        // 將檔案內容解析為 CSV 陣列
        $lines = explode("\n", $fileContent);
        if (count($lines) > 1) {
            $headers = str_getcsv(trim($lines[0]));

            // 去除欄位名稱的特殊字元
            $headers = array_map(function ($header) {
                return trim(preg_replace('/[^\x20-\x7E]/', '', $header));
            }, $headers);

            $errorCount = 0;

            for ($i = 1; $i < count($lines); $i++) {
                $line = trim($lines[$i]);
                if ($line === "") {
                    continue;
                }
                $fields = str_getcsv($line);

                // 檢查欄位數是否匹配
                if (count($fields) === count($headers)) {
                    $data = [];
                    foreach ($headers as $index => $header) {
                        if (in_array($header, $tableColumns)) {
                            $column = $header;
                            $value = mysqli_real_escape_string($link, trim($fields[$index]));
                            $data["`$column`"] = "'$value'";
                        }
                    }

                    if (!empty($data)) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        $query = "INSERT INTO test ($columns) VALUES ($values)";
                        
                        // 顯示 SQL 語句以進行調試
                        echo "<pre>執行的 SQL 查詢: $query</pre>";

                        if (!mysqli_query($link, $query)) {
                            echo "資料庫錯誤: " . mysqli_error($link) . "<br>";
                        }
                    } else {
                        echo "第 " . ($i + 1) . " 行資料無法映射到資料表的欄位，已跳過。<br>";
                        $errorCount++;
                    }
                } else {
                    echo "第 " . ($i + 1) . " 行欄位數量不匹配，已跳過。<br>";
                    $errorCount++;
                }
            }

            if ($errorCount > 0) {
                echo "共有 $errorCount 行資料無法處理，已跳過。<br>";
            } else {
                echo "檔案資料已成功寫入資料庫。";
            }
        } else {
            echo "CSV 檔案內容格式錯誤。";
        }
    } else {
        echo "檔案上傳失敗，請重試。";
    }
}

mysqli_close($link);
?>
