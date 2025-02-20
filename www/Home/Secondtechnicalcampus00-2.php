<?php
// 啟用 Session
session_start();

// 設定資料庫連線資訊
$servername = "127.0.0.1";  
$username = "HCHJ";        
$password = "xx435kKHq";   
$dbname = "HCHJ";         

// 建立資料庫連線
$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    error_log("資料庫連線失敗: " . mysqli_connect_error());
    die("系統目前無法處理您的請求，請稍後再試。");
}

// 設定使用 UTF8 編碼
mysqli_query($link, 'SET NAMES UTF8');

// 確認使用者已登入
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['file']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if ($fileExtension !== 'csv') {
            echo "<script>alert('請上傳 CSV 檔案！'); window.location.href = 'school1.php';</script>";
            exit;
        }

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle);
            if ($headers) {
                // 移除標題欄位前可能存在的 BOM 與多餘空白（視情況調整）
                $headers[0] = trim($headers[0], "\xEF\xBB\xBF");
                $headers = array_map('trim', $headers);
                // 若 CSV 為中文，這邊 strtolower 其實不會有太大影響，可保留
                $headers = array_map('strtolower', $headers);

                // 有效欄位對應（不會匯入篩選條件欄位）
                $validMapping = [
                    '學校名稱' => 'name',
                    '科系名稱' => 'department',
                    '縣市名稱' => 'address',
                ];

                // 篩選條件欄位（不加入資料庫）
                $filterFields = [
                    '日間∕進修別' => 'day_night_type',
                    '等級別'   => 'level_type',
                    '體系別'   => 'system_type',
                ];

                $columnMapping = [];
                $filterMapping = [];

                foreach ($headers as $index => $header) {
                    // 因為 CSV 為中文，tolower 不影響，所以直接比對
                    if (isset($validMapping[$header])) {
                        $columnMapping[$index] = $validMapping[$header];
                    }
                    if (isset($filterFields[$header])) {
                        $filterMapping[$index] = $filterFields[$header];
                    }
                }

                // 檢查必備欄位是否存在
                if (empty($columnMapping) || !in_array('system_type', $filterMapping)) {
                    fclose($handle);
                    exit;
                }

                $newEntries = [];
                $successCount = 0;

                while (($row = fgetcsv($handle)) !== FALSE) {
                    $data = [];
                    $dayNightType = '';
                    $levelType = '';
                    $systemType = '';

                    // 提取篩選條件值，並移除所有空白（避免 "D 日" 與 "D日" 之間的差異）
                    foreach ($filterMapping as $index => $filterColumn) {
                        if (isset($row[$index])) {
                            $value = trim($row[$index]);
                            // 移除所有空白字元
                            $value = str_replace(' ', '', $value);
                            if ($filterColumn == 'day_night_type') {
                                $dayNightType = $value;
                            }
                            if ($filterColumn == 'level_type') {
                                $levelType = $value;
                            }
                            if ($filterColumn == 'system_type') {
                                $systemType = $value;
                            }
                        }
                    }

                    // 判斷條件：只允許符合篩選條件的資料進入
                    if ($dayNightType == 'D日' && $levelType == 'C二技' && $systemType == '2技職') {
                        // 建立要匯入的資料（只包含有效欄位）
                        foreach ($columnMapping as $index => $dbColumn) {
                            if (isset($row[$index])) {
                                $value = mysqli_real_escape_string($link, trim($row[$index]));
                                $data["`$dbColumn`"] = "'$value'";
                            }
                        }

                        if (!empty($data)) {
                            $columns = implode(", ", array_keys($data));
                            $values = implode(", ", array_values($data));

                            $schoolName = trim($data['`name`'], "'");

                            // 不做重複檢查，直接插入
                            $query = "INSERT INTO test ($columns) VALUES ($values)";
                            if (mysqli_query($link, $query)) {
                                $successCount++;
                                $newEntries[] = $schoolName;
                            }
                        }
                    }
                }
                fclose($handle);

                session_write_close();
                flush();

                if ($successCount > 0) {
                    $schoolsList = implode(', ', $newEntries);
                    echo "<script>
                        alert('成功匯入 $successCount 筆新資料，請新增此校園科系：$schoolsList');
                        window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('無新資料，已更新');
                        window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
                    </script>";
                }
                exit;
            } else {
                echo "<script>alert('CSV 檔案格式錯誤，標題行缺失'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
            }
        } else {
            echo "<script>alert('無法開啟 CSV 檔案。'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
        }
    } else {
        echo "<script>alert('檔案不可為空白'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
    }
}

mysqli_close($link);


/*
// 檢查請求是否為 POST 方法
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 確保上傳的檔案存在且沒有錯誤
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['file']['tmp_name']; // 取得暫存檔路徑
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)); // 取得副檔名

        // 確保上傳的檔案為 CSV 格式
        if ($fileExtension !== 'csv') {
            echo "<script>alert('請上傳 CSV 檔案！'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
            exit;
        }

        // 開啟 CSV 檔案
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle); // 讀取 CSV 第一行作為標題

            if ($headers) {
                // 清理標題內容並轉為小寫，方便比對
                $headers = array_map('trim', $headers);
                $headers = array_map('strtolower', $headers);

                // 設定 CSV 標題對應到資料庫欄位的對應關係
                $validMapping = [
                    '代碼' => 'id',
                    '學校名稱' => 'name',
                    '公/私立' => 'public_private',
                    '電話' => 'phone',
                    '地址' => 'address',
                    '網址' => 'website',
                    '體系別' => 'system_type'
                ];

                // 建立 CSV 欄位與資料庫欄位的對應關係
                $columnMapping = [];
                foreach ($headers as $index => $header) {
                    if (isset($validMapping[$header])) {
                        $columnMapping[$index] = $validMapping[$header];
                    }
                }

                // 如果關鍵欄位缺失則終止
                if (empty($columnMapping) || !in_array('system_type', $columnMapping)) {
                    fclose($handle);
                    exit;
                }

                $newEntries = []; // 用來存放新學校的名稱
                $successCount = 0; // 計算成功新增的筆數

                // 逐行讀取 CSV 檔案內容
                while (($row = fgetcsv($handle)) !== FALSE) {
                    $data = [];
                    $isVocational = false;
                    $recordId = '';

                    // 將 CSV 欄位對應到資料庫欄位
                    foreach ($columnMapping as $index => $dbColumn) {
                        if (isset($row[$index])) {
                            $value = mysqli_real_escape_string($link, trim($row[$index]));
                            if ($dbColumn == 'id') {
                                $recordId = $value;
                            }
                            if ($dbColumn == 'system_type') {
                                if (strpos(trim($value), "[2]技職") !== false) {
                                    $isVocational = true;
                                }
                            }
                            if ($dbColumn == 'system_type' && strlen($value) > 5) {
                                $value = substr($value, 0, 5);
                            }
                            $data["`$dbColumn`"] = "'$value'";
                        }
                    }

                    // 若符合技職體系，則檢查是否已存在於資料庫
                    if ($isVocational && !empty($data)) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        // 取得學校名稱
                        $schoolName = trim($data['`name`'], "'");

                        // 查詢該學校是否已存在
                        $checkQuery = "SELECT COUNT(*) as count FROM Secondskill WHERE name = '$schoolName'";
                        $result = mysqli_query($link, $checkQuery);
                        $row = mysqli_fetch_assoc($result);

                        // 若學校不存在，則新增到資料庫
                        if ($row['count'] == 0) {
                            $query = "INSERT INTO Secondskill ($columns) VALUES ($values)";
                            if (mysqli_query($link, $query)) {
                                $successCount++;
                                $newEntries[] = $schoolName;
                            }
                        }
                    }

                }
                fclose($handle);

                // 確保資料庫寫入後即時生效
                session_write_close();
                flush();

                // 根據結果顯示對應訊息
                if ($successCount > 0) {
                    $schoolsList = implode(', ', $newEntries);
                    echo "<script>
                        alert('成功匯入 $successCount 筆新資料，請新增此校園科系：$schoolsList');
                        window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
                    </script>";
                } else {
                    echo "<script>
                        alert('無新資料，已更新');
                        window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';
                    </script>";
                }
                exit;
            } else {
                echo "<script>alert('CSV 檔案格式錯誤，標題行缺失'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
            }
        } else {
            echo "<script>alert('無法開啟 CSV 檔案。'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
        }
    } else {
        echo "<script>alert('檔案不可為空白'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
    }
}
*/
// 關閉資料庫連線
mysqli_close($link);
?>