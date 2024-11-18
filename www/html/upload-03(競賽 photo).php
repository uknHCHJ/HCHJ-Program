<html>
    <body>
<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

$link = mysqli_connect($servername, $username, $password, $dbname);

if ($link){
    mysqli_query($link,'SET NAMES UTF8');
}else{
    echo "不正確連接資料庫</br>" . mysqli_connect_error();
}
if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    $imageName = basename($image['name']);
    $imageTmpName = $image['tmp_name'];
    $imageSize = $image['size'];
    $imageError = $image['error'];
    $imageType = $image['type'];

    // 設定允許的圖片類型
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $imageExt = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));

    if (in_array($imageExt, $allowed)) {
        if ($imageError === 0) {
            if ($imageSize < 5000000) { // 限制檔案大小為 5MB
                $newImageName = uniqid('', true) . "." . $imageExt;
                $imageDestination = 'uploads/' . $newImageName;

                // 3. 移動圖片到 uploads 資料夾
                if (!is_dir('uploads')) {
                    mkdir('uploads', 0777, true); // 建立 uploads 資料夾
                }
                move_uploaded_file($imageTmpName, $imageDestination);

                // 4. 將圖片的路徑存入 MySQL 資料庫
                $sql = "INSERT INTO information (image_path) VALUES ('$imageDestination')";

                if (mysqli_query($link, $sql)) {
                    echo "圖片上傳並儲存成功！";
                } else {
                    echo "錯誤: " . $sql . "<br>" . mysqli_error($link);
                }
            } else {
                echo "檔案大小超過限制。";
            }
        } else {
            echo "上傳圖片時出現錯誤。";
        }
    } else {
        echo "不支援的圖片格式。";
    }
} else {
    echo "尚未選擇圖片。";
}
mysqli_close($link);
   ?>
   </body>
 </html>