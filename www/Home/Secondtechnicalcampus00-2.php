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
    error_log("資料庫連線失敗: " . mysqli_connect_error());
    die("無法連接到資料庫，請稍後再試。");
}

// 設置資料庫的編碼為 UTF-8
mysqli_query($link, 'SET NAMES UTF8');

// 確保使用者已登入，否則拒絕訪問
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 處理檔案上傳請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // 確認是否有檔案上傳且上傳成功
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("檔案上傳失敗，請檢查檔案並重試。");
        }

        $filePath = $_FILES['file']['tmp_name']; // 暫存檔案的路徑
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)); // 檢查檔案副檔名

        // 確保上傳的檔案是 CSV 格式
        if ($fileExtension !== 'csv') {
            throw new Exception("請上傳 CSV 檔案！");
        }

        // 開啟 CSV 檔案
        if (($handle = fopen($filePath, "r")) === FALSE) {
            throw new Exception("無法打開 CSV 檔案。");
        }

        $headers = fgetcsv($handle); // 讀取 CSV 的標題行

        if (!$headers) {
            throw new Exception("CSV 檔案格式錯誤，沒有標題行。");
        }

        // 處理標題行
        $headers = array_map('trim', $headers);  // 去除每個欄位名稱的多餘空白
        $headers = array_map('strtolower', $headers); // 將標題轉為小寫，方便比對

        $validMapping = [
            '代碼' => 'id',
            '學校名稱' => 'name',
            '公/私立' => 'Public/Private',
            '電話' => 'phone',
            '地址' => 'address',
            '網址' => 'website'
        ];

        // 找出 CSV 標題對應的資料表欄位名稱
        $columnMapping = [];
        foreach ($headers as $index => $header) {
            if (isset($validMapping[$header])) {
                $columnMapping[$index] = $validMapping[$header];
            }
        }

        if (empty($columnMapping)) {
            throw new Exception("CSV 檔案的標題行與資料表欄位名稱無匹配，無法插入資料！");
        }

        $lineNumber = 0; // 記錄目前處理的行號
        $errorCount = 0; // 記錄無法處理的行數

        // 逐行讀取 CSV 檔案中的數據
        while (($row = fgetcsv($handle)) !== FALSE) {
            $lineNumber++;

            // 準備要插入資料表的欄位和值
            $data = [];
            foreach ($columnMapping as $index => $dbColumn) {
                if (isset($row[$index])) {
                    $value = mysqli_real_escape_string($link, trim($row[$index]));
                    $data["`$dbColumn`"] = "'$value'";
                }
            }

            // 如果有有效的資料，執行 INSERT 操作
            if (!empty($data)) {
                $columns = implode(", ", array_keys($data));
                $values = implode(", ", array_values($data));
                $query = "INSERT INTO test ($columns) VALUES ($values)";

                if (!mysqli_query($link, $query)) {
                    error_log("第 $lineNumber 行資料庫錯誤: " . mysqli_error($link));
                    $errorCount++;
                }
            } else {
                error_log("第 $lineNumber 行資料無法映射到資料表的欄位，已跳過。");
                $errorCount++;
            }
        }

        fclose($handle); // 關閉檔案

        // 輸出處理結果
        if ($errorCount > 0) {
            echo "部分資料處理失敗，共跳過 $errorCount 行。請檢查日誌。";
        } else {
            echo "CSV 檔案資料已成功寫入資料庫。";
        }

    } catch (Exception $e) {
        echo $e->getMessage(); // 顯示簡單錯誤訊息
        error_log($e->getMessage()); // 記錄詳細錯誤
    }
}

// 關閉資料庫連線
mysqli_close($link);
?>
