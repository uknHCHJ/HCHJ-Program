<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>班級操作</title>
  <style>
    .button-container {
      margin: 20px;
      text-align: center;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      margin: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <?php 
    session_start();
    // 從 POST 取得班級資訊，若未傳入則預設為空字串
    $grade = isset($_POST['grade']) ? $_POST['grade'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';
  ?>
  
  <div class="button-container">
    <button id="reviewBtn">備審</button>
    <button id="orderBtn">志願序</button>
  </div>

  <script>
    // 等待文件內容載入完成
    document.addEventListener("DOMContentLoaded", function() {
      // 將 PHP 傳入的班級資訊傳給 JavaScript
      var grade = "<?php echo urlencode($grade); ?>";
      var classValue = "<?php echo urlencode($class); ?>";
      
      // 綁定點擊事件
      document.getElementById("reviewBtn").addEventListener("click", function() {
        window.location.href = "index-02.php?grade=" + grade + "&class=" + classValue;
      });
      
      document.getElementById("orderBtn").addEventListener("click", function() {
        window.location.href = "index-02.php?grade=" + grade + "&class=" + classValue;
      });
    });
  </script>
</body>
</html>
