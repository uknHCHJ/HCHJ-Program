<?php
session_start();
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);
if (!$link) {
    die("無法連接資料庫：" . mysqli_connect_error());
}
mysqli_query($link, 'SET NAMES UTF8');

if (!isset($_SESSION['user'])) {
    echo "<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['excel_file']['tmp_name'];
        if (!file_exists($file)) {
            die("檔案上傳失敗");
        }

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            // **跳過第一行（標題列）**
            array_shift($data);

            foreach ($data as $row) {
                $department  = mysqli_real_escape_string($link, trim($row[0] ?? ''));
                $grade       = mysqli_real_escape_string($link, trim($row[1] ?? ''));
                $class       = mysqli_real_escape_string($link, trim($row[2] ?? ''));
                $name        = mysqli_real_escape_string($link, trim($row[3] ?? ''));
                $user        = mysqli_real_escape_string($link, trim($row[4] ?? ''));
                $Permissions = trim($row[5] ?? '');
                $Permissions2 = trim($row[6] ?? '');

                if (empty($department) || empty($name)) {
                    echo "錯誤：缺少必要欄位 - 系所($department), 姓名($name)<br>";
                    continue;
                }

                // **如果 `$Permissions2` 為空，則填入 `9`**
                if ($Permissions2 === '') {
                    $Permissions2 = '9';
                }

                // **確保 `$Permissions` 和 `$Permissions2` 之間有 `,`**
                if (!empty($Permissions) && !empty($Permissions2)) {
                    $totalPermissions = "$Permissions,$Permissions2";
                } else {
                    $totalPermissions = $Permissions . $Permissions2;
                }

                $query = "INSERT INTO user (department, grade, class, name, user, password, Permissions) 
                          VALUES ('$department', '$grade', '$class', '$name', '$user', '$user', '$totalPermissions')";

                if (!mysqli_query($link, $query)) {
                    echo "資料插入失敗: " . mysqli_error($link);
                }
            }

            echo "<script>
                    alert('資料成功上傳並寫入資料庫');
                    window.location.href = 'Access-Control1.php';
                  </script>";

        } catch (Exception $e) {
            echo "檔案解析失敗: " . $e->getMessage();
        }

    } else if (isset($_POST['user'])) {
        $department  = mysqli_real_escape_string($link, trim($_POST['department'] ?? ''));
        $grade       = mysqli_real_escape_string($link, trim($_POST['grade'] ?? ''));
        $class       = mysqli_real_escape_string($link, trim($_POST['class'] ?? ''));
        $name        = mysqli_real_escape_string($link, trim($_POST['name'] ?? ''));
        $user        = mysqli_real_escape_string($link, trim($_POST['user'] ?? ''));
        $password    = $user;
        $Permissions = trim($_POST['permissions'] ?? '');
        $Permissions2 = trim($_POST['permissions2'] ?? '');

        // **如果 `$Permissions2` 為空，則填入 `9`**
        if ($Permissions2 === '') {
            $Permissions2 = '9';
        }

        // **確保 `$Permissions` 和 `$Permissions2` 之間有 `,`**
        if (!empty($Permissions) && !empty($Permissions2)) {
            $totalPermissions = "$Permissions,$Permissions2";
        } else {
            $totalPermissions = $Permissions . $Permissions2;
        }

        $query = "INSERT INTO user (department, grade, class, name, user, password, Permissions) 
                  VALUES ('$department', '$grade', '$class', '$name', '$user', '$password', '$totalPermissions')";

        if (mysqli_query($link, $query)) {
            echo "<script>
                    alert('新增成功: " . $user . "');
                    window.location.href = 'Access-Control1.php';
                  </script>";
        } else {
            echo "新增成員失敗：" . mysqli_error($link);
        }
    } else {
        echo "無效的請求。";
    }
}
?>
