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
            echo "<script>alert('請上傳 CSV 檔案！'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
            exit;
        }

        if (($handle = fopen($filePath, "r")) !== FALSE) {

            // 清除舊資料並重置自動編號
            $truncateQuery = "TRUNCATE TABLE test";
            if (!mysqli_query($link, $truncateQuery)) {
                error_log("清除舊資料失敗：" . mysqli_error($link));
                echo "<script>alert('無法清除舊資料'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
                exit;
            }

            $headers = fgetcsv($handle);
            if ($headers) {
                // 移除標題欄位前可能存在的 BOM 與多餘空白
                $headers[0] = trim($headers[0], "\xEF\xBB\xBF");
                $headers = array_map('trim', $headers);
                $headers = array_map('strtolower', $headers);

                // 有效欄位對應（匯入資料庫用，不包含篩選欄位）
                $validMapping = [
                    '學校名稱' => 'name',
                    '科系名稱' => 'department',
                    '縣市名稱' => 'address',
                ];

                // 篩選條件欄位（僅用於判斷，不匯入資料庫）
                $filterFields = [
                    '日間∕進修別' => 'day_night_type',
                    '等級別'   => 'level_type',
                    '體系別'   => 'system_type',
                ];

                $columnMapping = [];
                $filterMapping = [];

                foreach ($headers as $index => $header) {
                    if (isset($validMapping[$header])) {
                        $columnMapping[$index] = $validMapping[$header];
                    }
                    if (isset($filterFields[$header])) {
                        $filterMapping[$index] = $filterFields[$header];
                    }
                }

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

                    // 提取並處理篩選條件欄位的值
                    foreach ($filterMapping as $index => $filterColumn) {
                        if (isset($row[$index])) {
                            $value = trim($row[$index]);
                            // 移除所有空白，避免因空白字元影響比對
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

                    // 僅允許符合下列條件的資料進入：日間∕進修別為 D日、等級別為 C二技、體系別為 2技職
                    if ($dayNightType == 'D日' && $levelType == 'C二技' && $systemType == '2技職') {
                        // 組合需要匯入資料庫的欄位和值
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

                            // 直接插入資料，不檢查重複
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
                        alert('成功匯入');
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
?>
