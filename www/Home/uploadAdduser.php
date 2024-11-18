<?php
session_start();  // 確保啟動 session
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link){
    mysqli_query($link,'SET NAMES UTF8');
} else {
    echo "無法連接資料庫：" . mysqli_connect_error();
}

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['excel_file']) && $_FILES['excel_file']['error'] == UPLOAD_ERR_OK) {
        if (!file_exists($file)) {
            die("檔案上傳失敗");
        }   
         // 載入 Excel 檔案
    try {
        /*$spreadsheet = IOFactory::load($file);  // 讀取 Excel 檔案
        $sheet = $spreadsheet->getActiveSheet();  // 取得當前工作表
        $data = $sheet->toArray();  // 將工作表資料轉換為陣列*/

        // 逐行處理 Excel 資料
        foreach ($data as $row) {
            // 假設 Excel 欄位的順序為 ID、姓名、科系、年級
            $department = isset($row[0]) ? mysqli_real_escape_string($link, $row[0]) : '';
            $grade = isset($row[1]) ? mysqli_real_escape_string($link, $row[1]) : '';
            $class = isset($row[2]) ? mysqli_real_escape_string($link, $row[2]) : '';
            $name = isset($row[3]) ? mysqli_real_escape_string($link, $row[3]) : '';
            $user = isset($row[4]) ? mysqli_real_escape_string($link, $row[4]) : '';
            $Permissions = isset($row[5]) ? mysqli_real_escape_string($link, $row[5]) : '';
            $Permissions2 = isset($row[6]) ? mysqli_real_escape_string($link, $row[6]) : '';
            

            // 檢查資料是否完整
            if (empty($department) || empty($name)) {
                continue;  // 跳過不完整的資料
            }

                $totalPermissions = $Permissions . ',' . $Permissions2;
                $query = "INSERT INTO user (department, grade, class, name, user, password, Permissions) 
                      VALUES ('$department', '$grade', '$class', '$name','$user','$user','$totalPermissions')";
            // 建立 SQL 插入語句
            

            // 執行 SQL 插入
            $result = mysqli_query($link, $query);

            if (!$result) {
                echo "資料插入失敗: " . mysqli_error($link);
            }
        }

        echo "資料成功上傳並寫入資料庫";
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
                
        $totalPermissions = $Permissions . ',' . $Permissions2;
        $query = "INSERT INTO user (department,grade,class,name,user,password,Permissions) VALUES ('$department','$grade','$class','$name','$user','$password','$totalPermissions')";

        if (mysqli_query($link, $query)) {
            echo "<script>
                    alert('新增成功: " . $user . "');
                    window.location.href = 'Access-Control1.php'; // 重定向到新增成員頁面
                  </script>";
        } else {
            echo "新增成員失敗：" . mysqli_error($link);
        }
    } else {
        echo "無效的請求。";
    }
} else {
    echo "無效的請求。";
}
?>
