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

mysqli_query($link, 'SET NAMES UTF8');

// 確保使用者已登入
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

                $newEntries = [];
                $successCount = 0;

                while (($row = fgetcsv($handle)) !== FALSE) {
                    $data = [];
                    $isVocational = false;
                    $recordId = '';

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

                    if ($isVocational && !empty($data)) {
                        $columns = implode(", ", array_keys($data));
                        $values = implode(", ", array_values($data));

                        // 直接判斷資料是否已存在
                        $checkQuery = "SELECT COUNT(*) as count FROM Secondskill WHERE id = '$recordId'";
                        $result = mysqli_query($link, $checkQuery);
                        $row = mysqli_fetch_assoc($result);

                        if ($row['count'] == 0) {
                            $query = "INSERT INTO Secondskill ($columns) VALUES ($values)";
                            if (mysqli_query($link, $query)) {
                                $successCount++;
                                $newEntries[] = trim($data['`name`'], "'");
                            }
                        }
                    }
                }
                fclose($handle);

                // 確保畫面即時更新
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
?>
