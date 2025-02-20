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
            // **清空舊資料**
            if (!mysqli_query($link, "TRUNCATE TABLE school") || !mysqli_query($link, "TRUNCATE TABLE Department")) {
                error_log("清除舊資料失敗：" . mysqli_error($link));
                echo "<script>alert('無法清除舊資料'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
                exit;
            }

            $headers = fgetcsv($handle);
            if ($headers) {
                $headers[0] = trim($headers[0], "\xEF\xBB\xBF"); // 去除 BOM
                $headers = array_map('trim', $headers);
                $headers = array_map('strtolower', $headers);

                // **對應 CSV 欄位到資料庫**
                $columnMapping = [
                    '學校名稱' => 'name',
                    '科系' => 'department_name'
                ];

                // **建立欄位索引對應**
                $mappedIndexes = [];
                foreach ($headers as $index => $header) {
                    if (isset($columnMapping[$header])) {
                        $mappedIndexes[$index] = $columnMapping[$header];
                    }
                }

                // **確認 CSV 欄位完整**
                if (!isset($mappedIndexes[0]) || !isset($mappedIndexes[1])) { 
                    fclose($handle);
                    exit;
                }

                $successSchoolCount = 0;
                $successDepartmentCount = 0;
                $importedSchools = [];    // 記錄已匯入的學校名稱
                $importedDepartments = []; // 記錄已匯入的科系名稱

                while (($row = fgetcsv($handle)) !== FALSE) {
                    $data = [];
                    foreach ($mappedIndexes as $index => $dbColumn) {
                        $value = trim($row[$index] ?? '');
                        if ($dbColumn === 'name') {
                            $schoolName = $value; // **取得學校名稱**
                        } elseif ($dbColumn === 'department_name') {
                            $departmentName = $value; // **取得科系名稱**
                        }
                        $data["`$dbColumn`"] = "'" . mysqli_real_escape_string($link, $value) . "'";
                    }

                    // **學校名稱: 確保不重複**
                    if (!empty($schoolName) && !in_array($schoolName, $importedSchools)) {
                        $schoolQuery = "INSERT INTO school (`name`) VALUES ('" . mysqli_real_escape_string($link, $schoolName) . "')";
                        if (mysqli_query($link, $schoolQuery)) {
                            $successSchoolCount++;
                            $importedSchools[] = $schoolName; // **記錄已匯入的學校**
                        }
                    }

                    // **科系名稱: 確保不重複**
                    if (!empty($departmentName) && !in_array($departmentName, $importedDepartments)) {
                        $departmentQuery = "INSERT INTO Department (`department_name`) VALUES ('" . mysqli_real_escape_string($link, $departmentName) . "')";
                        if (mysqli_query($link, $departmentQuery)) {
                            $successDepartmentCount++;
                            $importedDepartments[] = $departmentName; // **記錄已匯入的科系**
                        }
                    }
                }
                fclose($handle);

                session_write_close();
                flush();

                echo "<script>alert('成功匯入 {$successSchoolCount} 所學校、{$successDepartmentCount} 個科系資料'); window.location.href = '/~HCHJ/Home/Secondtechnicalcampus00-1.php';</script>";
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
