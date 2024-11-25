<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}
$userData = $_SESSION['user'];
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$username = $userData['name']; // 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$query1 = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$query2 = sprintf("SELECT name FROM `user` WHERE name = '%s'", mysqli_real_escape_string($link, $username));
$result = mysqli_query($link, $query1);
$result = mysqli_query($link, $query2);
?>

<!DOCTYPE html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>查看學生志願序</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
  <!-- Place favicon.ico in the root directory -->

  <!-- ========================= CSS here ========================= -->
  <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
  <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/tiny-slider.css">
  <link rel="stylesheet" href="assets/css/glightbox.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>

<body>
  <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

  <!-- ========================= preloader start ========================= -->
  <div class="preloader">
    <div class="loader">
      <div class="ytp-spinner">
        <div class="ytp-spinner-container">
          <div class="ytp-spinner-rotator">
            <div class="ytp-spinner-left">
              <div class="ytp-spinner-circle"></div>
            </div>
            <div class="ytp-spinner-right">
              <div class="ytp-spinner-circle"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- preloader end -->

  <!-- ========================= header start ========================= -->
  <header class="header navbar-area">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index-02.php">
              <img src="schoolimages/uknlogo.png" alt="Logo">
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
              aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="toggler-icon"></span>
              <span class="toggler-icon"></span>
              <span class="toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
              <ul id="nav" class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="page-scroll" href="contact02-1.php">個人資料</a>
                </li>
                <li class="nav-item">
                  <a class="nav-item dd-menu">比賽資訊</a>
                  <ul class="sub-menu">
                    <li class="nav-item"><a href="blog-03(競賽).php">比賽資訊</a></li>
                    <li class="nav-item"><a href="create-03.php">新增</a></li>
                    <li class="nav-item"><a href="delete-03.php">編輯比賽資訊</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-item dd-menu">二技校園網</a>
                  <ul class="sub-menu">
                    <li class="nav-item"><a href="blog-03(競賽).php">新增校園</a></li>
                    <li class="nav-item"><a href="create-03.php">編輯詳細資料</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="student02-1.php">學生管理</a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="/~HCHJ/Permission.php">切換使用者</a>
                </li>
            </div> <!-- navbar collapse -->
          </nav> <!-- navbar -->
        </div>
      </div> <!-- row -->
    </div> <!-- container -->

  </header>
  <!-- ========================= header end ========================= -->
  <!-- ========================= page-banner-section start ========================= -->
  <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="banner-content">
            <h2 class="text-white">查看學生志願序</h2>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                  <li class="breadcrumb-item active" aria-current="page">查看志願序</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========================= page-banner-section end ========================= -->

  <!-- ========================= service-section start ========================= -->
  <section id="service" class="service-section pt-130 pb-100">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
          <div class="section-title text-center mb-55">
            <span class="wow fadeInDown" data-wow-delay=".2s">查看學生志願序</span>
          </div>
        </div>
      </div>
      <style>
        /* 按鈕樣式 */
        .button {
          background-color: #4CAF50;
          color: white;
          font-size: 16px;
          font-weight: bold;
          padding: 10px 20px;
          margin: 5px;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
          transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
          background-color: #45a049;
          transform: scale(1.05);
        }

        .button:active {
          animation: click-animation 0.5s forwards;
        }

        /* 表格樣式設定 */
        table {
          width: 100%;
          border-collapse: collapse;
        }

        th,
        td {
          padding: 10px;
          border: 1px solid #ccc;
          text-align: center;
        }

        th {
          background-color: #6A7C92;
          color: white;
        }

        td {
          background-color: #f9f9f9;
        }

        /* 表格淡入動畫 */
        @keyframes fadeIn {
          from {
            opacity: 0;
            transform: translateY(-20px);
          }

          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .table-container {
          animation: fadeIn 1s ease-in-out;
        }

        /* ... (other styles) */

        #loading {
          display: none;
          position: absolute;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5);
          z-index: 10;
          /* Place the loading indicator on top of other content */
          justify-content: center;
          align-items: center;
        }

        #loading:before {
          content: "";
          display: inline-block;
          border-radius: 4px;
          width: 100px;
          height: 100px;
          border: 6px solid #f3f3f3;
          border-color: #f3f3f3 transparent #f3f3f3 transparent;
          animation: spin 1s linear infinite;
        }

        @keyframes spin {
          0% {
            transform: rotate(0deg);
          }

          100% {
            transform: rotate(360deg);
          }
        }

        /* 設定欄位寬度  姓名 */
        #data-table th:nth-child(1) {
          width: 200px;
        }

        /* 名稱 */
        #data-table th:nth-child(2) {
          width: 500px;
        }

        /* 日期 */
        #data-table th:nth-child(3) {
          width: 500px;
        }

        /* 證明 */
        #data-table th:nth-child(4) {
          width: 500px;
        }
      </style>

      <div id="loading">  
        <p>正在載入資料...</p>
      </div>

      <div class="table-container">
        <table id="data-table" border="1">
          <?php
          // 確保正確接收 user 的資料
          if (isset($_POST['user']) && !empty($_POST['user'])) {
            $file_id = intval($_POST['user']); // 強制轉換為整數以避免安全問題
          
            // 使用 JOIN 將 Preferences 表中的 school_id 和 department_id 與 School 和 Department 表連接
            $sql = "
                SELECT 
                    p.serial_number,
                    s.school_name,
                    d.department_name
                FROM 
                    Preferences p
                LEFT JOIN 
                    School s ON p.school_id = s.school_id
                LEFT JOIN 
                    Department d ON p.department_id = d.department_id
                WHERE 
                    p.user = '$file_id'
            ";

            $result = mysqli_query($link, $sql);

            // 檢查是否有符合條件的資料
            if ($result && mysqli_num_rows($result) > 0) {
              echo '<thead>';
              echo '<tr>';
              echo '<th>序號</th>';
              echo '<th>二技校園</th>';
              echo '<th>科系</th>';
              echo '</tr>';
              echo '</thead>';
              echo '<tbody>';

              while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['serial_number']) . '</td>';
                echo '<td>' . htmlspecialchars($row['school_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['department_name']) . '</td>';
                echo '</tr>';
              }

              echo '</tbody>';
            } else {
              echo "<p>沒有找到符合條件的資料</p>";
            }
          } else {
            // 當 POST 資料缺失
            echo "<p>未接收到正確的使用者資料，請重新提交表單。</p>";
          }
          ?>
        </table>
      </div>


      <?php
      // 確定當前頁面，默認為第 1 頁
      $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; //將 page 參數轉換為整數
      $records_per_page = 5; // 每頁顯示 10 筆資料
      $offset = ($page - 1) * $records_per_page; //參數沒有傳遞（即 isset() 返回 false），則會使用 1 作為預設值，這意味著當用戶沒有指定頁數時，會顯示第 1 頁
      $total_pages = ceil($total_records / $records_per_page); //假設總記錄數是 53 條，每頁顯示 10 條記錄：53 / 10 = 5.3。使用 ceil() 之後，會變成 6，也就是總頁數為 6。
      ?>

      <!-- 分頁 -->
      <div style="margin-top: 20px;"> <!-- 加入 margin-top，讓分頁和表格之間有間隔 -->
        <nav aria-label="Page navigation">
          <ul class="pagination justify-content-center">
            <!-- 上一頁按鈕，當前頁為1時禁用 -->
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
              <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                <span aria-hidden="true">&laquo;</span>
              </a>
            </li>
            <!-- 分頁顯示：動態生成每一頁的頁碼 -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
              <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor; ?>
            <!-- 下一頁按鈕，當前頁為最後一頁時禁用 -->
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
              <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                <span aria-hidden="true">&raquo;</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>

      <!-- 回到上一頁按鈕，與分頁區域靠近 -->
      <div style="text-align: center; margin-top: 10px;">
        <input type="button" class="button" onclick="history.go(-1)" value="回到上一頁">
      </div>

      <!-- ========================= service-section end ========================= -->




      <!-- ========================= client-logo-section start ========================= -->
      <section class="client-logo-section pt-100">
        <div class="container">
          <div class="client-logo-wrapper">
            <div class="client-logo-carousel d-flex align-items-center justify-content-between">
              <div class="client-logo">
                <img src="schoolimages/uknim.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/uknbm.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/uknanime.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/uknbaby.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/uknenglish.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/ukneyes.jpg" alt="">
              </div>
              <div class="client-logo">
                <img src="schoolimages/uknnurse.jpg" alt="">
              </div>


            </div>
          </div>
        </div>
      </section>
      <!-- ========================= client-logo-section end ========================= -->

      <!-- ========================= footer start ========================= -->
      <footer class="footer pt-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                <a href="index-04.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
                <p class="mb-30 footer-desc">©康寧大學資訊管理科製作</p>
              </div>
            </div>
            <div class="col-xl-3 col-lg-4 col-md-6">
              <div class="footer-widget mb-1 wow fadeInLeft" data-wow-delay=".8s">

                <ul class="footer-contact">
                  <h3>關於我們</h3>
                  <p>(02)2632-1181/0986-212-566</p>
                  <p>台北校區：114 臺北市內湖區康寧路三段75巷137號</p>
                </ul>
                <style>
                  .footer .row {
                    display: flex;
                    align-items: center;
                    /* 垂直居中 */
                    justify-content: space-between;
                    /* 讓兩個區塊分居左右 */
                  }

                  .footer-widget {
                    text-align: right;
                    /* 讓「關於學校」內容靠右對齊 */
                  }
                </style>
              </div>
            </div>
          </div>

          <div class="copyright-area">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="footer-social-links">
                  <ul class="d-flex">
                    <li><a href="https://www.facebook.com/UKNunversity"><i class="lni lni-facebook-filled"></i></a></li>
                    <li><a href="https://www.instagram.com/ukn_taipei/"><i class="lni lni-instagram-filled"></i></a>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
      <!-- ========================= footer end ========================= -->


      <!-- ========================= scroll-top ========================= -->
      <a href="#" class="scroll-top">
        <i class="lni lni-arrow-up"></i>
      </a>

      <!-- ========================= JS here ========================= -->
      <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
      <script src="assets/js/contact-form.js"></script>
      <script src="assets/js/count-up.min.js"></script>
      <script src="assets/js/tiny-slider.js"></script>
      <script src="assets/js/isotope.min.js"></script>
      <script src="assets/js/glightbox.min.js"></script>
      <script src="assets/js/wow.min.js"></script>
      <script src="assets/js/imagesloaded.min.js"></script>
      <script src="assets/js/main.js"></script>
</body>

</html>