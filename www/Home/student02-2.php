<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>班級操作</title>
  <style>
    /* Modal 樣式 */
    .modal {
      display: none; /* 預設隱藏 */
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fefefe;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 600px;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
    }
    /* 其他樣式 */
    .button-container {
      margin: 20px;
      text-align: center;
    }
    button {
      padding: 10px 20px;
      font-size: 16px;
      margin: 10px;
    }
  </style>
</head>
<body>
  <div class="button-container">
    <button id="reviewBtn">備審</button>
    <button id="orderBtn">志願序</button>
  </div>

  <!-- 學生名單的 Modal -->
  <div id="studentModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2>學生名單</h2>
      <ul>
        <?php
        session_start();

        // 資料庫連接
        $link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
        if (!$link) {
          die("資料庫連接失敗: " . mysqli_connect_error());
        }

        // 檢查是否有 grade 與 class 傳入
        if (isset($_POST['grade']) && isset($_POST['class'])) {
          $grade = mysqli_real_escape_string($link, $_POST['grade']);
          $class = mysqli_real_escape_string($link, $_POST['class']);

          // 查詢符合條件的學生
          $sql = "SELECT * FROM user WHERE grade = '$grade' AND class = '$class'";
          $result = mysqli_query($link, $sql);

          if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
              // 僅顯示學生姓名
              echo '<li>' . htmlspecialchars($row['name']) . '</li>';
            }
          } else {
            echo '<li>此班級目前沒有學生。</li>';
          }
        } else {
          echo '<li>資料不完整，請重新嘗試。</li>';
        }
        mysqli_close($link);
        ?>
      </ul>
    </div>
  </div>

  <script>
    // 取得 modal 與按鈕元素
    var modal = document.getElementById("studentModal");
    var reviewBtn = document.getElementById("reviewBtn");
    var closeBtn = document.getElementsByClassName("close")[0];
    var orderBtn = document.getElementById("orderBtn");

    // 按下「備審」按鈕時，顯示 modal
    reviewBtn.onclick = function() {
      modal.style.display = "block";
    };

    // 點選右上角的關閉按鈕關閉 modal
    closeBtn.onclick = function() {
      modal.style.display = "none";
    };

    // 點選 modal 區域外部時也關閉 modal
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    };

    // 「志願序」按鈕點擊事件，可依需求實作
    orderBtn.onclick = function() {
      alert("志願序功能尚未實作！");
    };
  </script>
</body>
</html>
