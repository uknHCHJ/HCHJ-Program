<?php
// 啟用 Session
session_start();

// 資料庫連線設置
$servername = "127.0.0.1"; // 資料庫伺服器位址
$username = "HCHJ"; // 資料庫使用者名稱
$password = "xx435kKHq"; // 資料庫密碼
$dbname = "HCHJ"; // 資料庫名稱

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    error_log("資料庫連線失敗: " . mysqli_connect_error()); // 記錄錯誤日誌
    die("系統目前無法處理您的請求，請稍後再試。"); // 顯示錯誤訊息並終止程式執行
}

// 設置資料庫的編碼為 UTF-8
mysqli_query($link, 'SET NAMES UTF8');

// 確保使用者已登入
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。"); // 如果未登入，則終止執行
}

// 處理檔案上傳請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 確認是否有檔案上傳
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['file']['tmp_name']; // 取得暫存檔案路徑
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)); // 取得檔案副檔名

        // 確保上傳的檔案是 CSV 格式
        if ($fileExtension !== 'csv') {
            echo "<script>
                alert('請上傳 CSV 檔案！');
                window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
              </script>";
            exit;
        }

        // 開啟 CSV 檔案
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle); // 讀取 CSV 的標題行

            // 驗證標題行
            if ($headers) {
                $headers = array_map('trim', $headers); // 去除空白
                $headers = array_map('strtolower', $headers); // 轉小寫方便匹配

                // 定義標題與資料庫欄位的對應關係
                $validMapping = [
                    '代碼' => 'id',
                    '學校名稱' => 'name',
                    '公/私立' => 'Public/Private',
                    '電話' => 'phone',
                    '地址' => 'address',
                    '網址' => 'website',
                    '體系別' => 'system_type'
                ];

                $columnMapping = [];
                foreach ($headers as $index => $header) {
                    if (isset($validMapping[$header])) {
                        $columnMapping[$index] = $validMapping[$header]; // 建立標題與資料庫欄位的映射關係
                    }
                }

                // 檢查是否有 system_type 欄位
                if (empty($columnMapping) || !in_array('system_type', $columnMapping)) {
                    fclose($handle);
                    exit;
                }

                $lineNumber = 0;
                $errorCount = 0;
                $successCount = 0;

                // 逐行讀取 CSV 資料
                while (($row = fgetcsv($handle)) !== FALSE) {
                    $lineNumber++;
                    $data = [];
                    $isVocational = false; // 判斷是否為技職學校

                    foreach ($columnMapping as $index => $dbColumn) {
                        if (isset($row[$index])) {
                            $value = mysqli_real_escape_string($link, trim($row[$index])); // 轉義特殊字元

                            // 判斷體系別是否包含 "技職"
                            if ($dbColumn == 'system_type') {
                                if (strpos(trim($value), "[2]技職") !== false) {
                                    $isVocational = true;
                                } else {
                                    $isVocational = false;
                                }
                            }

                            // 限制 system_type 長度
                            if ($dbColumn == 'system_type' && strlen($value) > 5) {
                                $value = substr($value, 0, 5);
                            }
                            $data["`$dbColumn`"] = "'$value'";
                        }
                    }

                    // 只有當體系別為技職時執行資料庫插入
                    if ($isVocational && !empty($data)) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        // 使用 INSERT INTO ... ON DUPLICATE KEY UPDATE 避免重複資料
                        $query = "INSERT INTO Secondskill ($columns) VALUES ($values)
                                  ON DUPLICATE KEY UPDATE " . implode(", ", array_map(function ($col) {
                                      return "$col = VALUES($col)";
                                  }, array_keys($data)));

                        if (!mysqli_query($link, $query)) {
                            $errorCount++;
                        } else {
                            $successCount++;
                        }
                    } 
                }

                fclose($handle);

                // 結果提示
                echo "<script>
                alert('成功匯入 $successCount 筆技職學校資料。');
                window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
              </script>";
                if ($errorCount > 0) {
                    echo "<script>
                alert('共有 $errorCount 行資料無法匯入，請檢查檔案內容格式是否正確。');
                window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
              </script>";
                }
            } else {
                echo "<script>
                alert('CSV 檔案格式錯誤，標題行缺失');
                window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
              </script>";
            }
        } else {
            echo "<script>
            alert('無法開啟 CSV 檔案。');
            window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
          </script>";
        }
    } else {
        echo "<script>
            alert('檔案不可為空白');
            window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
          </script>";
    }
}

// 關閉資料庫連線
mysqli_close($link);
?>
