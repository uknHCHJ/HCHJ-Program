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
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
        $file = $_FILES['excel_file']['tmp_name']; // 取得檔案
        if (!file_exists($file)) {
            die("檔案上傳失敗");
        }

        try {
            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray();

            foreach ($data as $row) {
                $department = isset($row[0]) ? mysqli_real_escape_string($link, $row[0]) : '';
                $grade = isset($row[1]) ? mysqli_real_escape_string($link, $row[1]) : '';
                $class = isset($row[2]) ? mysqli_real_escape_string($link, $row[2]) : '';
                $name = isset($row[3]) ? mysqli_real_escape_string($link, $row[3]) : '';
                $user = isset($row[4]) ? mysqli_real_escape_string($link, $row[4]) : '';
                $Permissions = isset($row[5]) ? mysqli_real_escape_string($link, $row[5]) : '';
                $Permissions2 = isset($row[6]) ? mysqli_real_escape_string($link, $row[6]) : '';

                if (empty($department) || empty($name)) {
                    echo "錯誤：缺少必要欄位 - 系所($department), 姓名($name)<br>";
                    continue;
                }

                // 過濾權限
                $permissionsArray = array_filter([$Permissions, $Permissions2], function($p) {
                    return $p !== '9'; // 過濾掉 9
                });
                $totalPermissions = implode(',', $permissionsArray);

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
        $department = mysqli_real_escape_string($link, $_POST['department']);
        $grade = mysqli_real_escape_string($link, $_POST['grade']);
        $class = mysqli_real_escape_string($link, $_POST['class']);
        $name = mysqli_real_escape_string($link, $_POST['name']);
        $user = mysqli_real_escape_string($link, $_POST['user']);
        $password = mysqli_real_escape_string($link, $_POST['user']);
        $Permissions = mysqli_real_escape_string($link, $_POST['permissions']);
        $Permissions2 = mysqli_real_escape_string($link, $_POST['permissions2']);   

        $permissionsArray = array_filter([$Permissions, $Permissions2], function($p) {
            return $p !== '9';
        });
        $totalPermissions = implode(',', $permissionsArray);

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