<?php
// 啟動 Session (只需一次)
session_start();

// ------------------------------
// 取得班級資訊
// ------------------------------
// 優先從 POST 取得班級資料，如果沒有則嘗試從 GET 取得
$grade = '';
$class = '';
if (isset($_POST['grade']) && isset($_POST['class'])) {
    $grade = $_POST['grade'];
    $class = $_POST['class'];
} elseif (isset($_GET['grade']) && isset($_GET['class'])) {
    $grade = $_GET['grade'];
    $class = $_GET['class'];
} else {
    // 如果都沒有提供班級資料，顯示錯誤訊息並終止程式
    echo "<p>班級資訊未提供，請重新嘗試。</p>";
    exit();
}
?>
<!doctype html>
<html lang="zh-TW">
<head>
  <meta charset="UTF-8">

  <style>
    /* 整個按鈕容器的樣式 */
    .button-container {
      margin: 20px;
      text-align: center;
    }
    /* 共用的按鈕樣式 */
    .action-button {
      padding: 10px 20px;             /* 按鈕內邊距 */
      font-size: 16px;                /* 文字大小 */
      margin: 10px;                   /* 外邊距 */
      cursor: pointer;                /* 滑鼠指標變手形 */
      border: none;                   /* 無邊框 */
      border-radius: 5px;             /* 圓角效果 */
      color: #ffffff;                 /* 文字顏色為白色 */
      transition: background-color 0.3s ease; /* 平滑變色效果 */
    }
    /* 查看備審按鈕的專屬樣式：綠色系 */
    .review-button {
      background-color: #28a745;      /* 基本綠色 */
    }
    .review-button:hover {
      background-color: #218838;      /* 滑鼠懸停時變深 */
    }
    /* 查看志願序按鈕的專屬樣式：藍色系 */
    .order-button {
      background-color: #007bff;      /* 基本藍色 */
    }
    .order-button:hover {
      background-color: #0069d9;      /* 滑鼠懸停時變深 */
    }
    /* 標題樣式 */
    h2 {
      text-align: center;
      margin-top: 30px;
    }
  </style>
</head>
<body>
 

  <!-- 顯示【查看備審】與【查看志願序】兩個按鈕 -->
  <div class="button-container">
    <!-- 查看備審按鈕 -->
    <button id="reviewButton" class="action-button review-button">查看備審</button>
    <!-- 查看志願序按鈕 -->
    <button id="orderButton" class="action-button order-button">查看志願序</button>
  </div>

  <script>
    // 等待文件載入完成後再執行
    document.addEventListener("DOMContentLoaded", function() {
      // 將 PHP 傳入的班級資訊傳給 JavaScript，使用 encodeURIComponent 處理特殊字元
      var grade = "<?php echo urlencode($grade); ?>";
      var classValue = "<?php echo urlencode($class); ?>";

      // 當使用者點擊【查看備審】按鈕時，跳轉到 review_class.php
      document.getElementById("reviewButton").addEventListener("click", function() {
        // 跳轉並傳送班級資訊作為 GET 參數
        window.location.href = "index-02.php?grade=" + grade + "&class=" + classValue;
      });

      // 當使用者點擊【查看志願序】按鈕時，跳轉到 order_class.php
      document.getElementById("orderButton").addEventListener("click", function() {
        // 跳轉並傳送班級資訊作為 GET 參數
        window.location.href = "order_class.php?grade=" + grade + "&class=" + classValue;
      });
    });
  </script>
</body>
</html>
