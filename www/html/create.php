
<?php
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    echo "資料庫連接失敗: " . mysqli_connect_error();
}
/*$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link){
    mysqli_query($link,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
}*/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 使用 real_escape_string 防止 SQL 注入
    $servername = "localhost";
    $username = "HCHJ";
    $password = "xx435kKHq";
    $dbname = "HCHJ";
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 使用 real_escape_string 防止 SQL 注入
        $name = $conn->real_escape_string($_POST['name']);
        //echo $name;
        $in = $conn->real_escape_string($_POST['inform']);
        $lk = $conn->real_escape_string($_POST['link']);

        // 構建插入語句
        $sql = "INSERT INTO information (`ID`,`name`,`inform`,`link`) VALUES (NULL,$name,$in,$lk)";
        $stmt = $conn->prepare($sql);
        //$stmt->bind_param("information", $name, $information, $lk);
        $stmt = $conn->prepare("INSERT INTO information (`ID`,`name`,`inform`,`link`) VALUES (NULL,$name,$in,$lk)");
        if ($stmt === false) {
            // If preparation fails, output error details
            die('MySQL prepare error: ' . $conn->errno . ': ' . $conn->error);
        }
    }
        // 執行語句並檢查是否成功
        if ($stmt->execute()) {
             echo "新增成功";
        } else {
            echo "錯誤: " . $stmt->error;
        }

// 關閉語句和連接
$stmt->close();
$conn->close();

    }
   /*mysqli_query($conn, $sql);
    //echo "新增成功!<br>";
    //echo "姓名：$name<br>Email： $email<br>年齡：$age";
    if (mysqli_query($conn, $sql)) {
        echo "新增用戶成功!";
    } else {
        echo "新增用戶失敗: " . mysqli_error($conn);
    }*/
?>

