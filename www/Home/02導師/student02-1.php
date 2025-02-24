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
  <title>學生備審管理</title>
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
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
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
                                    <a class="nav-item dd-menu">學生管理</a>
                                    <ul class="sub-menu">
                                    <li class="nav-item"><a href="student02-1.php">學生備審管理</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序總覽</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02(2).php">繳交志願序</a></li>
                                        <li class="nav-item"><a href="settime02-1.php">志願序開放時間</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="Schoolnetwork1-02.php">二技校園網</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">比賽資訊</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog-02.php">頁首</a></li>
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
                                    <a class="page-scroll" href="../logout.php">登出</a>
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
            <h2 class="text-white">學生備審管理</h2>
            
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========================= page-banner-section end ========================= -->

  <!-- ========================= page-404-section end ========================= -->
  <section class="page-404-section pt-130 pb-130">
    <div class="container-fluid"> <!-- 使用 container-fluid -->
      <div class="row">
        <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
          <div class="section-title text-center mb-55">
            <h1 class="wow fadeInDown" data-wow-delay=".2s">選擇班級</h1>
            <style>
              /*body {
                font-family: Arial, sans-serif;
                text-align: center;
                background-color: #f4f4f9;
                padding: 20px;
              }*/

              /*h2 {
                color: #333;
              }*/

              /* 班級按鈕 */
              .class-button {
                background-color: #4CAF50;
                color: white;
                font-size: 18px;
                font-weight: bold;
                padding: 12px 24px;
                margin: 5px;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
              }

              /* 班級按鈕 */
              .class-button {
                background-color: #4CAF50;
                color: white;
                font-size: 18px;
                font-weight: bold;
                padding: 12px 24px;
                margin: 5px;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
                transition: all 0.3s ease;
              }

              .class-button:hover {
                background-color: #45a049;
                transform: scale(1.05);
              }

              /* 功能按鈕樣式 */
              .download-button {
                background-color: #007bff;
                color: white;
                font-size: 18px;
                font-weight: bold;
                width: 500px;
                height: 55px;
                padding: 10px;
                border: none;
                border-radius: 15px;
                cursor: pointer;
                box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.15);
                transition: transform 0.2s ease, background-color 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
              }

              .download-button:hover {
                background-color: #0056b3;
                transform: scale(1.08);
              }

              /* 功能按鈕容器 */
              #menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
                /* 每個按鈕之間的間距 */
                margin-top: 20px;
                width: 100%;
                /* 容器寬度設為 100% */
              }

              /* 第一排 6 個按鈕 */
              #menu :nth-child(-n+6) {
                flex: 1 1 calc(100% / 6 - 10px);
              }

              /* 第二排 5 個按鈕 */
              #menu :nth-child(n+7) {
                flex: 1 1 calc(100% / 5 - 10px);
              }

              /* 返回按鈕 */
              #back-button {
                margin-top: 20px;
                background-color: #dc3545;
                color: white;
                border: none;
                padding: 14px 24px;
                border-radius: 10px;
                font-size: 18px;
                font-weight: bold;
                cursor: pointer;
                transition: all 0.3s ease;
              }

              #back-button:hover {
                background-color: #c82333;
                transform: scale(1.05);
              }

              /* 隱藏元素 */
              .hidden {
                display: none;
              }
            </style>
            <div id="class-buttons">
              <?php foreach ($gradeClassPairs as $pair): ?>
                <button class="class-button" data-class="<?php echo htmlspecialchars($pair); ?>">
                  <?php echo htmlspecialchars($pair); ?>
                </button>
              <?php endforeach; ?>
            </div>

            <div id="menu" class="hidden"></div>
            <button id="back-button" class="hidden" onclick="location.reload();">回上一頁</button>

            <script>
              document.addEventListener("DOMContentLoaded", function () {
                const classButtons = document.querySelectorAll(".class-button");
                const menuDiv = document.getElementById("menu");
                const backButton = document.getElementById("back-button");
                const classButtonsContainer = document.getElementById("class-buttons");

                classButtons.forEach(button => {
                  button.addEventListener("click", function () {
                    const selectedClass = this.getAttribute("data-class");

                    // 隱藏所有班級按鈕
                    classButtons.forEach(btn => btn.classList.add("hidden"));

                    // 只顯示被點擊的班級按鈕
                    this.classList.remove("hidden");
                    this.classList.add("selected-class");

                    // 取得後端資料
                    fetch("student02-2.php?class=" + selectedClass)
                      .then(response => {
                        if (!response.ok) throw new Error("HTTP 錯誤，狀態碼: " + response.status);
                        return response.json();
                      })
                      .then(data => {
                        menuDiv.innerHTML = "";
                        data.forEach(item => {
                          const btn = document.createElement("button");
                          btn.className = "download-button";
                          btn.textContent = item.name;
                          btn.onclick = () => window.location.href = item.url;
                          menuDiv.appendChild(btn);
                        });
                        menuDiv.classList.remove("hidden");
                        backButton.classList.remove("hidden");
                      })
                      .catch(error => console.error("載入功能按鈕時出錯:", error));
                  });
                });

                // 回上一頁按鈕
                backButton.addEventListener("click", function () {
                  menuDiv.classList.add("hidden");
                  this.classList.add("hidden");

                  // 顯示所有班級按鈕
                  classButtons.forEach(btn => {
                    btn.classList.remove("hidden");
                    btn.classList.remove("selected-class");
                  });
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

  <!-- ========================= footer start ========================= -->
  <footer class="footer pt-100">
    <div class="container">
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
            <a href="index-04.html" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
            <p class="mb-30 footer-desc">©康寧大學資訊管理科五年孝班 洪羽白、陳子怡、黃瑋晴、簡琨諺 共同製作</p>
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
                <li><a href="https://www.instagram.com/ukn_taipei/"><i class="lni lni-instagram-filled"></i></a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
  <!-- ========================= footer end ========================= -->


  <!-- ========================= 卷軸 ========================= -->
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