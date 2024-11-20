<?php
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 確認是否有傳入比賽ID
if (isset($_GET['ID'])) {
  $id = $_GET['ID'];

  // 檢查表單是否已提交
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $ID=$_GET['ID'];
      $name = $_POST['name'];
      $inform = $_POST['inform'];
      $link = $_POST['link'];
      $image = $_FILES['image'];

      // 檢查是否有上傳新圖片
      if ($image['error'] === 0) {
          // 取得圖片的二進制數據
          $imageData = file_get_contents($image['tmp_name']);
      } else {
          // 圖片上傳失敗，顯示錯誤信息
          echo "圖片上傳失敗";
          exit;
      }

      // 準備更新資料庫的SQL語句
      $sql = "UPDATE information SET name = ?, inform = ?, link = ?, image = ? WHERE ID = ?";

      // 使用預備語句來避免SQL注入
      if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param("ssssi", $name, $inform, $link, $imageData, $id);

          // 執行更新操作
          if ($stmt->execute()) {
            echo "比賽資訊更新成功!";
            header("location:Contestupdate1-02.php?ID=".$ID);
          } else {
              echo "比賽資訊更新失敗：" . $stmt->error;
              header("location:Contestupdate1-02.php?ID=".$ID);
          }

          // 釋放語句
          $stmt->close();
      } else {
          echo "準備語句失敗：" . $conn->error;
      }
  }
} else {
  echo "未指定比賽ID";
}

// 關閉資料庫連線
$conn->close();
?>