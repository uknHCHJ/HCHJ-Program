<?php
// 顯示所有錯誤訊息，便於調試
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 啟用 Session 機制
session_start();

// 資料庫連線設置
$servername = "127.0.0.1"; // 資料庫伺服器地址
$username = "HCHJ";         // 資料庫使用者名稱
$password = "xx435kKHq";    // 資料庫密碼
$dbname = "HCHJ";           // 資料庫名稱

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    die("資料庫連線失敗: " . mysqli_connect_error()); // 若連線失敗則終止程式並顯示錯誤
}

// 設置資料庫的編碼為 UTF-8
mysqli_query($link, 'SET NAMES UTF8');

// 確保使用者已登入，否則拒絕訪問
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 取得資料表 "test" 的欄位名稱，並存入陣列
$tableColumns = [];
$result = mysqli_query($link, "DESCRIBE test"); // 查詢資料表結構
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tableColumns[] = strtolower($row['Field']); // 將欄位名稱轉為小寫後存入陣列
    }
    mysqli_free_result($result); // 釋放資源
} else {
    die("無法取得資料表欄位資訊: " . mysqli_error($link));
}

// 處理檔案上傳請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 確認是否有檔案上傳且上傳成功
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['file']['tmp_name']; // 暫存檔案的路徑
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)); // 檢查檔案副檔名

        // 確保上傳的檔案是 CSV 格式
        if ($fileExtension !== 'csv') {
            echo "請上傳 CSV 檔案！";
            exit;
        }

        // 開啟 CSV 檔案
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle); // 讀取 CSV 的標題行

            // 確保標題行存在並進行處理
            if ($headers) {
                $headers = array_map('trim', $headers);  // 去除每個欄位名稱的多餘空白
                $headers = array_map('strtolower', $headers); // 將標題轉為小寫，方便比對

                $errorCount = 0; // 記錄無法處理的行數
                $lineNumber = 0; // 記錄目前處理的行號

                // 找出 CSV 檔案中的欄位與資料表欄位的交集
                $validColumns = array_intersect($headers, $tableColumns);

                if (empty($validColumns)) {
                    echo "CSV 檔案的標題行與資料表欄位名稱無匹配，無法插入資料！";
                    fclose($handle);
                    exit;
                }

                // 逐行讀取 CSV 檔案中的數據
                while (($row = fgetcsv($handle)) !== FALSE) {
                    $lineNumber++;

                    // 準備要插入資料表的欄位和值
                    $data = [];

                    foreach ($headers as $index => $header) {
                        if (isset($row[$index]) && in_array($header, $validColumns)) {
                            $column = mysqli_real_escape_string($link, $header); // 避免 SQL 注入
                            $value = mysqli_real_escape_string($link, trim($row[$index])); // 去除值的多餘空白並防注入
                            $data["`$column`"] = "'$value'"; // 將欄位和值加入資料陣列
                        }
                    }

                    // 額外檢查並處理 `name` 欄位
                    if (isset($row[array_search('name', $headers)])) {
                        $nameValue = mysqli_real_escape_string($link, trim($row[array_search('name', $headers)])); // 找到對應的 `name` 值
                        $data["`name`"] = "'$nameValue'"; // 將 `name` 加入資料陣列
                    }

                    // 如果有有效的資料，執行 INSERT 操作
                    if (!empty($data)) {
                        $columns = implode(", ", array_keys($data)); // 拼接欄位名稱
                        $values = implode(", ", array_values($data)); // 拼接欄位值

                        $query = "INSERT INTO test ($columns) VALUES ($values)"; // 組合 SQL 查詢語句
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

                fclose($handle); // 關閉檔案

                // 輸出處理結果
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

// 關閉資料庫連線
mysqli_close($link);
?>