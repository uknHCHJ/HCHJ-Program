<?php
// 啟用 Session
session_start();

// 資料庫連線設置
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

// 設置資料庫的編碼為 UTF-8
mysqli_query($link, 'SET NAMES UTF8');

// 確保使用者已登入
if (!isset($_SESSION['user'])) {
    die("未登入，請先登入後再操作。");
}

// 處理檔案上傳請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
        $filePath = $_FILES['file']['tmp_name'];
        $fileExtension = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

        if ($fileExtension !== 'csv') {
            echo "<script>alert('請上傳 CSV 檔案！'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
            exit;
        }

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $headers = fgetcsv($handle);
            if ($headers) {
                $headers = array_map('trim', $headers);
                $headers = array_map('strtolower', $headers);
                
                $validMapping = [
                    '代碼' => 'id',
                    '學校名稱' => 'name',
                    '公/私立' => 'public_private',
                    '電話' => 'phone',
                    '地址' => 'address',
                    '網址' => 'website',
                    '體系別' => 'system_type'
                ];

                $columnMapping = [];
                foreach ($headers as $index => $header) {
                    if (isset($validMapping[$header])) {
                        $columnMapping[$index] = $validMapping[$header];
                    }
                }

                if (empty($columnMapping) || !in_array('system_type', $columnMapping)) {
                    fclose($handle);
                    exit;
                }

                $existingSchools = [];
                $result = mysqli_query($link, "SELECT id, name FROM Secondskill");
                while ($row = mysqli_fetch_assoc($result)) {
                    $existingSchools[$row['id']] = $row['name'];
                }

                $lineNumber = 0;
                $errorCount = 0;
                $successCount = 0;
                $newEntries = [];
                $warningMessages = [];

                while (($row = fgetcsv($handle)) !== FALSE) {
                    $lineNumber++;
                    $data = [];
                    $isVocational = false;
                    $schoolID = null;
                    $schoolName = null;

                    foreach ($columnMapping as $index => $dbColumn) {
                        if (isset($row[$index])) {
                            $value = mysqli_real_escape_string($link, trim($row[$index]));

                            if ($dbColumn == 'id') {
                                $schoolID = $value;
                            } elseif ($dbColumn == 'name') {
                                $schoolName = $value;
                            } elseif ($dbColumn == 'system_type') {
                                $isVocational = (strpos(trim($value), "[2]技職") !== false);
                            }
                            
                            $data["`$dbColumn`"] = "'$value'";
                        }
                    }

                    if ($isVocational && !empty($data)) {
                        if (isset($existingSchools[$schoolID])) {
                            if ($existingSchools[$schoolID] !== $schoolName) {
                                $warningMessages[] = "⚠️ 學校代碼: $schoolID 的名稱與資料庫內不符，請確認是否需要更改或新增科系。";
                            }
                        } else {
                            $newEntries[] = "$schoolName ($schoolID)";
                        }

                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        $query = "REPLACE INTO Secondskill ($columns) VALUES ($values)";
                        if (!mysqli_query($link, $query)) {
                            $errorCount++;
                        } else {
                            $successCount++;
                        }
                    }
                }
                fclose($handle);

                $message = "成功匯入 $successCount 筆技職學校資料。";
                if ($errorCount > 0) {
                    $message .= "\n共有 $errorCount 行資料無法匯入，請檢查檔案內容格式是否正確。";
                }
                if (!empty($warningMessages)) {
                    $message .= "\n\n" . implode("\n", $warningMessages);
                }
                if (!empty($newEntries)) {
                    $message .= "\n\n以下學校為新資料，請確認是否新增科系: \n" . implode("\n", $newEntries);
                }
                
                echo "<script>alert('$message'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
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

// 關閉資料庫連線
mysqli_close($link);
?>
