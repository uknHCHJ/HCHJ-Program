<?php
session_start();
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

if (!isset($_SESSION['user'])) {
    echo("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}

$userData = $_SESSION['user'];
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$username= $userData['name']; // 例如從 SESSION 中獲取 user_id
$userId= $userData['user'];


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
  <title>留言板</title>
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
        style="background-image: url('assets/img/bg/common-bg.svg')">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="banner-content">
                        <h2 class="text-white">導師留言板</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" aria-current="page"><a href="javascript:void(0)">首頁</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">導師留言板</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= page-banner-section end ========================= -->

    <!-- ========================= alerts-section start ========================= -->
        <!-- 新增留言區域 -->
        <section class="service-section">  
    <div class="form-container container mt-4">
        <h3>歡迎，<?php echo htmlspecialchars($username); ?>！</h3>
        <form action="messageboard-01(留言板後端).php" method="post">
            <label for="message">新增留言：</label>
            <textarea id="message" name="message" class="form-control" rows="3" required></textarea><br>
            <button type="submit" class="btn btn-info">送出</button>
        </form>
    </div>
</section>

<section class="mt-4">
    <div class="message-list container">
        <h4>留言列表</h4>
        <?php
        if (!empty($comments)) {
            foreach ($comments as $comment) {
                echo '<div class="alert alert-success">';
                // 顯示留言者的名字
                echo '<strong>' . htmlspecialchars($comment['user']) . '：</strong>';
                // 顯示留言內容
                echo htmlspecialchars($comment['message']);
                echo '</div>';
            }
        } else {
            echo '<p class="text-muted">目前沒有留言。</p>';
        }
        ?>
    </div>
</section>
<style>
    /* 置中新增留言區域 */
    .alerts-section .row.justify-content-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* 置中留言區域 */
    .alerts-section .col-md-6 {
        max-width: 800px; /* 設定最大寬度 */
        width: 100%;      /* 寬度自適應 */
        text-align: center;
    }

    /* 設定表單內的按鈕置中 */
    .alerts-section .btn-primary {
        display: block;
        margin: 0 auto;
    }

    /* 修改alert樣式 */
    .message-list .alert {
        margin-bottom: 20px; /* 每條留言卡片之間的間隔 */
        text-align: left;
        border-radius: 8px;  /* 增加圓角 */
        padding: 15px;
        background-color: #e0f7fa; /* 背景顏色更柔和 */
        border: 1px solid #b2ebf2; /* 背景邊框 */
        width: 100%; /* 留言寬度自適應 */
        max-width: 800px; /* 最大寬度為800px */
        margin: 10px auto; /* 置中顯示 */
    }

    /* 設定alert文字樣式 */
    .message-list .alert a {
        font-size: 16px;
        text-decoration: none;
        color: #333;
    }

    /* 留言懸停效果 */
    .message-list .alert:hover {
        background-color: #b2ebf2;  /* 滑鼠懸停時改變顏色 */
    }
</style>
    <!-- ========================= alerts-section end ========================= -->

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
                            <a href="index-02.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
                                align-items: center; /* 垂直居中 */
                                justify-content: space-between; /* 讓兩個區塊分居左右 */
                                }
                                .footer-widget {                                   
                                text-align: right; /* 讓「關於學校」內容靠右對齊 */
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