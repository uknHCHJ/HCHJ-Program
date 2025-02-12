<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

if (!isset($_SESSION['user'])) {
  echo ("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
  exit();
}

$userData = $_SESSION['user'];
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$username = $userData['name']; // 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];

$permissions = explode(",", $userData['Permissions']); // 權限以逗號分隔
$grades = explode(",", $userData['grade']);  // 年級以逗號分隔
$classes = explode(",", $userData['class']);  // 班級以逗號分隔

// 將年級和班級組合成唯一的鍵
$gradeClassPairs = [];
foreach ($grades as $grade) {
  foreach ($classes as $class) {
    $pair = $grade . $class;
    if (!in_array($pair, $gradeClassPairs)) {
      $gradeClassPairs[] = $pair;
    }
  }
}
?>
<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>查看學生志願序</title>
  <!-- 確認已正確載入 jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <li class="nav-item"><a href="index-02.php">首頁</a></li>
                </li>
                <li class="nav-item">
                  <a class="nav-item dd-menu">個人資料</a>
                  <ul class="sub-menu">
                    <li class="nav-item"><a href="contact02-1.php">查看個人資料</a></li>
                    <li class="nav-item"><a href="/~HCHJ/changepassword.html">修改密碼</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="student02-1.php">學生管理</a>
                </li>
                <li class="nav-item">
                  <a class="nav-item dd-menu">二技校園網</a>
                  <ul class="sub-menu">
                    <li class="nav-item"><a href="Schoolnetwork1-02.php">首頁</a></li>
                    <li class="nav-item"><a href="AddSchool1-02.php">新增校園</a></li>
                    <li class="nav-item"><a href="SchoolEdit1-02.php">編輯詳細資料</a></li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a class="nav-item dd-menu">比賽資訊</a>
                  <ul class="sub-menu">
                    <li class="nav-item"><a href="Contestblog-02.php">查看</a></li>
                    <li class="nav-item"><a href="AddContest1-02.php">新增</a></li>
                    <li class="nav-item"><a href="ContestEdin1-02.php">編輯</a></li>
                  </ul>
                </li>


                <li class="nav-item">
                  <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                </li>
                <li class="nav-item">
                  <a class="page-scroll" href="/~HCHJ/Permission.php">切換使用者</a>
                </li>
                <li class="nav-item">
                  <a href="javascript:void(0)" onclick="submitLogout()">登出</a>
                </li>
            </div> <!-- navbar collapse -->
          </nav> <!-- navbar -->
        </div>
      </div> <!-- row -->
    </div> <!-- container -->

  </header>
  <!-- ========================= header end ========================= -->

  <!-- ========================= page-banner-section start ========================= -->
  <section class="page-banner-section pt-75 pb-75 img-bg"
    style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="banner-content">
            <h2 class="text-white">學生管理</h2>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                  <li class="breadcrumb-item active" aria-current="page">查看學生備審</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========================= page-banner-section end ========================= -->

  <!-- ========================= page-404-section end ========================= -->
  <section class="page-404-section pt-130 pb-130">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
          <div class="section-title text-center mb-55">
            <span class="wow fadeInDown" data-wow-delay=".2s">帶班班級名單</span>
            <style>
              /* 按鈕樣式 */
              /* 按鈕樣式 */
              .download-button {
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
                transition: transform 0.2s ease;
              }

              .download-button:hover {
                transform: scale(1.05);
              }

              .download-button:active {
                animation: click-animation 0.5s forwards;
              }

              /*備審*/
              .downloadreview {
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
                transition: transform 0.2s ease;
              }

              .downloadreview:hover {
                transform: scale(1.05);
              }

              .downloadreview:active {
                animation: click-animation 0.5s forwards;
              }

              /*留言板*/
              .messageboard {
                background-color: #17a2b8;
                color: white;
                font-size: 16px;
                font-weight: bold;
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease;
              }

              .messageboard:hover {
                transform: scale(1.05);
              }

              .messageboard:active {
                animation: click-animation 0.5s forwards;
              }

              /*競賽*/
              .viewcompetition {
                background-color: #ffc107;
                color: white;
                font-size: 16px;
                font-weight: bold;
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease;
              }

              .viewcompetition:hover {
                transform: scale(1.05);
              }

              .viewcompetition:active {
                animation: click-animation 0.5s forwards;
              }

              /*志願*/
              .viewapplicationorder {
                background-color: #007bff;
                color: white;
                font-size: 16px;
                font-weight: bold;
                padding: 10px 20px;
                margin: 5px;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                transition: transform 0.2s ease;
              }

              .viewapplicationorder:hover {
                transform: scale(1.05);
              }

              .viewapplicationorder:active {
                animation: click-animation 0.5s forwards;
              }


              /* 表格容器 */
              .data-table {
                width: 100%;
                max-width: 1500px;
                border-collapse: collapse;
                animation: fadeIn 1s ease-in-out;
                margin: 0 auto;
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

              /* Loading 標誌樣式 */
              #loading {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100vh;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 10;
                justify-content: center;
                align-items: center;
              }

              #loading:before {
                content: "";
                display: inline-block;
                border-radius: 50%;
                width: 50px;
                height: 50px;
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

              /* 設定欄位寬度 */
              .data-table th:nth-child(1) {
                width: 150px;
                /* 學號 */
              }

              .data-table th:nth-child(2) {
                width: 200px;
                /* 姓名 */
              }

              .data-table th:nth-child(3) {
                width: 250px;
                /* 備審 */
              }

              .data-table th:nth-child(4) {
                width: 250px;
                /* 留言板 */
              }

              .data-table th:nth-child(5) {
                width: 250px;
                /* 競賽 */
              }

              .data-table th:nth-child(6) {
                width: 250px;
                /* 志願序 */
              }
            </style>
            <div class="classList" id="classList">
              <?php
              // 動態生成按鈕，根據唯一的 grade-class 組合
              foreach ($gradeClassPairs as $pair) {
                $grade = substr($pair, 0, -1);
                $class = substr($pair, -1);
                echo '<button type="button" class="download-button" data-grade="' . htmlspecialchars($grade) . '" data-class="' . htmlspecialchars($class) . '">';
                echo htmlspecialchars($grade) . htmlspecialchars($class);
                echo '</button>';

              }
              ?>
            </div>

            <div id="menu" style="display:none;">
              <ul id="menuList"></ul>
            </div>

            <script>
              document.addEventListener("DOMContentLoaded", function () {
                console.log("✅ JavaScript 成功載入！");

                const classList = document.getElementById("classList"); // 取得班級按鈕的容器
                const menu = document.getElementById("menu");
                const menuList = document.getElementById("menuList");

                // **確認 menuList 是否存在**
                if (!menuList) {
                  console.error("menuList 不存在，請確認 HTML 是否有 <ul id='menuList'></ul>");
                  return;
                }

                if (!classList) {
                  console.error("lassList 不存在，請確認 HTML 是否有 <div id='classList'></div>");
                  return;
                }

                // **監聽班級按鈕的點擊**
                classList.addEventListener("click", function (event) {
                  if (event.target.classList.contains("download-button")) {
                    console.log("✅ 班級按鈕被點擊，載入功能清單...");
                    menu.style.display = "block"; // 顯示功能清單

                    fetch("student02-2.php")
                      .then(response => response.json())
                      .then(data => {
                        console.log("📥 從後端獲取的數據：", data);

                        if (data.error) {
                          console.error("❌ 後端錯誤:", data.error);
                          return;
                        }

                        menuList.innerHTML = ""; // 清空按鈕列表

                        data.forEach(button => {
                          const li = document.createElement("li");
                          const a = document.createElement("a");
                          a.textContent = button.name;
                          a.href = button.url;
                          li.appendChild(a);
                          menuList.appendChild(li);
                        });

                        console.log("✅ 按鈕已載入，menuList 內容：", menuList.innerHTML);
                      })
                      .catch(error => console.error("❌ 載入按鈕失敗:", error));
                  }
                });
              });

            </script>
          </div>
        </div>
      </div>
    </div>
    </div>
  </section>
  <!-- ========================= page-404-section end ========================= -->

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
                <li><a href="https://www.facebook.com/UKNunversity"><i class="lni lni-facebook-filled"></i></a>
                </li>
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