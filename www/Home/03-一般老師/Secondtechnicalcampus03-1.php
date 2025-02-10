<?php
session_start();
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    die("資料庫連接失敗: " . mysqli_connect_error());
}
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$username = $userData['name'];
$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);
//if (mysqli_num_rows($result) > 0) {
// $userDetails = mysqli_fetch_assoc($result);  
//} else {
// echo "找不到使用者的詳細資料";
//}
?>
<!Doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>上傳二技校園</title>
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
                            <a class="navbar-brand" href="index-03.php">
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
                                        <a class="page-scroll" href="index-03.php" >首頁</a>
                                    </li>   
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu">個人資料</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="contact02-3.php">查看個人資料</a></li>
                                        <li class="nav-item"><a href="/~HCHJ/changepassword.html">修改密碼</a></li>
                                        </ul>
                                    </li>       
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu">二技校園網</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Schoolnetwork1.php">首頁</a></li>
                                        <li class="nav-item"><a href="AddSchool1.php">新增校園</a></li>
                                        <li class="nav-item"><a href="SchoolEdit1.php">編輯資訊</a></li>                                        
                                        </ul>
                                    </li>        
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu" >比賽資訊</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog1.php">查看</a></li>
                                            <li class="nav-item"><a href="AddContest1.php">新增</a></li>
                                            <li class="nav-item"><a href="ContestEdin1.php">編輯</a></li>
                                        </ul>
                                    </li>              
                                    <li class="nav-item">
                                            <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                                    </li>             
                                    <li class="nav-item">
                                        <a class="page-scroll" href="/~HCHJ/Permission.php" >切換使用者</a>
                                    </li>                                                    
                                    <li class="nav-item">
                                        <a class="page-scroll" href="../logout.php" >登出</a>
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
                        <h2 class="text-white">請上傳二技校園</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ========================= page-banner-section end ========================= -->

    <!-- ========================= feature-section start ========================= -->
    <!-- 表單區塊 -->
    <section id="service" class="service-section pt-130 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                    <div class="section-title text-center mb-55">
                        <span class="wow fadeInDown" data-wow-delay=".2s">新增二技校園</span>
                    </div>
                    <style>
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
                transition: background-color 0.3s ease, transform 0.2s ease;
              }

              .download-button:hover {
                background-color: #45a049;
                transform: scale(1.05);
              }

              .download-button:active {
                animation: click-animation 0.5s forwards;
              }
              /*確認*/
              .primary-button {
                background-color:rgb(76, 120, 175);
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

              .primary-button:hover {
                background-color:rgb(76, 120, 175);
                transform: scale(1.05);
              }

              .primary-button:active {
                animation: click-animation 0.5s forwards;
              }
              /*上一頁*/
              .secondary-button {
                background-color:rgba(25, 19, 19, 0.21);
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

              .secondary-button:hover {
                background-color:rgba(25, 19, 19, 0.21);
                transform: scale(1.05);
              }

              .secondary-button:active {
                animation: click-animation 0.5s forwards;
              }
              </style>
                </div>
            </div>
            <div class="table-container">
                <form id="permission-form" action="Secondtechnicalcampus00-2.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="imageUpload">上傳檔案(.csv)：</label>
                        <input type="file" id="file" name="file" class="form-control" accept=".csv">
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="user">帳號：</label>
                        <input type="text" id="user" name="user" class="form-control" value=<?php echo $userId; ?> readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="username">姓名：</label>
                        <input type="text" id="username" name="username" class="form-control" value=<?php echo $username; ?> readonly>
                    </div>
                    <button type="submit" class="primary-button">確認上傳</button>
                    <button type="button" class="download-button" onclick="window.location.href='https://depart.moe.edu.tw/ed4500/News_Content.aspx?n=63F5AB3D02A8BBAC&s=E23C5A6CA17DB8E2&sms=1FF9979D10DBF9F3';">下載更新資料</button>                    
                    <button type="button" class="secondary-button" onclick="window.history.back();">返回上一頁</button>
                </form>

            </div>
    </section>
    

    <!-- ========================= feature-section end ========================= -->
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
                        <a href="index-04.html" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
                                <li><a href="https://www.facebook.com/UKNunversity"><i
                                            class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="https://www.instagram.com/ukn_taipei/"><i
                                            class="lni lni-instagram-filled"></i></a></li>
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