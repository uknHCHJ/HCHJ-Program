<?php
session_start();

$userData = $_SESSION['user'];
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>比賽資訊</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
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
                                        <a class="page-scroll" href="contact-03(個人資料).php">個人資料</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="portfolio-03(二技校園網介紹).php">二技校園網介紹</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll active dd-menu" href="blog-03(競賽).php">比賽資訊</a>           
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="create-03.php">新增比賽資訊</a></li>
                                            <li class="nav-item"><a href="delete-03.php">修改/刪除比賽資訊</a></li>
                                        </ul>
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
                            <h2 class="text-white">比賽資訊</h2>
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

        <!-- ========================= blog-section end ========================= -->
        <section class="blog-section pt-130">
            <div class="container">
                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="left-side-wrapper">
                            <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                                <div class="blog-img">
                                    <a href="https://contest.bhuntr.com/tw/o0pc2d3a9a1km714eu/home/"><img src="schoolimages/IIIC.jpg" alt=""></a>
                                </div>
                                <div class="blog-content">
                                    <h4><a href="https://contest.bhuntr.com/tw/o0pc2d3a9a1km714eu/home/">2024第十五屆IIIC國際創新發明競賽</a></h4>
                                    <p>IIIC國際創新發明大賽自2010年在台北舉辦，至今共舉辦15年，本競賽以環境保護、綠能、醫療、生物科技和電子相關創意為核心。IIIC國際創新發明競賽的舉辦，除可提昇國人在上述領域中創新發明的素養，更可藉由比賽的氛圍，成為與國際接軌重要的經驗，亦為借重國際專家的成果分享，為國內未來發展知識經濟、培育創造力。每年共有來自 12 個不同國家,超過 400 項傑出發明作品參加。</p>

                                    <div class="blog-meta">
                                        <a href="https://contest.bhuntr.com/tw/o0pc2d3a9a1km714eu/home/" class="read-more-btn">Read More <i class="lni lni-angle-double-right"></i></a>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                                <div class="blog-img">
                                    <a href="https://www.csf.org.tw/csfnew/Home/NewsDetail/A96E4A477EE6DC1B"><img src="schoolimages/game.jpg" alt=""></a>
                                </div>
                                <div class="blog-content">
                                    <h4><a href="https://www.csf.org.tw/csfnew/Home/NewsDetail/A96E4A477EE6DC1B">2024程速勁賽之生存遊戲</a></h4>
                                    <p>做錯一步馬上淘汰無法前往下一關挑戰的程式競賽來囉!邀請所有對程式語言充滿熱情的程式好手們，一起來參加這場緊張刺激的專業對決，不要錯過這個舞台!立即報名參賽，展現你的實力，贏得屬於你的獎金！</p>

                                    <div class="blog-meta">
                                        <a href="https://www.csf.org.tw/csfnew/Home/NewsDetail/A96E4A477EE6DC1B" class="read-more-btn">Read More <i class="lni lni-angle-double-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                                <div class="blog-img">
                                    <a href="https://etutor2.itsa.org.tw/mod/itsaojcontest/news.php?id=127"><img src="schoolimages/ITSA.png" alt=""></a>
                                </div>
                                <div class="blog-content">
                                    <h4><a href="https://etutor2.itsa.org.tw/mod/itsaojcontest/news.php?id=127">2024年09月ITSA程式能力線上自我評量</a></h4>
                                    <p>為鼓勵程式自學平臺(E-Tutor)之使用者將所學實際應用，並練習解題和促進自我學
                                        習，特舉辦本線上評量。讓學生可先利用程式自學平臺(e-tutor)上所建置的題庫練習
                                        解題，再利用本線上評量進行自我檢測，了解自我學習狀況，以提升程式設計能力。</p>

                                    <div class="blog-meta">
                                        <a href="https://etutor2.itsa.org.tw/mod/itsaojcontest/news.php?id=127" class="read-more-btn">Read More <i class="lni lni-angle-double-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                                <div class="blog-img">
                                    <a href="https://ncpc.nsysu.edu.tw/p/412-1062-99.php?Lang=zh-tw"><img src="schoolimages/programming.png" alt=""></a>
                                </div>
                                <div class="blog-content">
                                    <h4><a href="https://ncpc.nsysu.edu.tw/p/412-1062-99.php?Lang=zh-tw">113 年度全國大專電腦軟體設計競賽</a></h4>
                                    <p>為鼓勵學生從事電腦軟體設計，提升我國資訊教育水準，特舉辦本競賽。</p>

                                    <div class="blog-meta">
                                        <a href="https://ncpc.nsysu.edu.tw/p/412-1062-99.php?Lang=zh-tw" class="read-more-btn">Read More <i class="lni lni-angle-double-right"></i></a>
                                    </div>
                                </div>
                            </div>
                           
            
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="sidebar-wrapper">
                            <div class="sidebar-box search-form-box mb-30">
                                <form action="#" class="search-form">
                                    <input type="text" placeholder="Search...">
                                    <button type="submit"><i class="lni lni-search-alt"></i></button>
                                </form>
                            </div>

                            <div class="sidebar-box recent-blog-box mb-30">
                                <h4>新公告</h4>
                                <div class="recent-blog-items">
                                    <div class="recent-blog mb-30">
                                        <div class="recent-blog-img">
                                            <img src="assets/img/blog/recent-blog-1.png" alt="">
                                        </div>
                                        <div class="recent-blog-content">
                                            <h5><a href="blog-single.html">Sed utper spiciunde iste natus erro.</a></h5>
                                            <span class="date">22 Julay, 2020</span>
                                        </div>
                                    </div>
                                    <div class="recent-blog mb-30">
                                        <div class="recent-blog-img">
                                            <img src="assets/img/blog/recent-blog-2.png" alt="">
                                        </div>
                                        <div class="recent-blog-content">
                                            <h5><a href="blog-single.html">Sed utper spiciunde iste natus erro.</a></h5>
                                            <span class="date">22 Julay, 2020</span>
                                        </div>
                                    </div>
                                    <div class="recent-blog">
                                        <div class="recent-blog-img">
                                            <img src="assets/img/blog/recent-blog-3.png" alt="">
                                        </div>
                                        <div class="recent-blog-content">
                                            <h5><a href="blog-single.html">Sed utper spiciunde iste natus erro.</a></h5>
                                            <span class="date">22 Julay, 2020</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                <h4>Popular Tags</h4>
                                <ul>
                                    <li><a href="javascript:void(0)">Web Design</a></li>
                                    <li><a href="javascript:void(0)">Wireframing</a></li>
                                    <li><a href="javascript:void(0)">Graphic Design</a></li>
                                    <li><a href="javascript:void(0)">Branding</a></li>
                                    <li><a href="javascript:void(0)">Analysis</a></li>
                                    <li><a href="javascript:void(0)">Web Development</a></li>
                                </ul>
                            </div>

                            <div class="sidebar-box mb-30">
                                <h4>Follow On</h4>
                                <div class="footer-social-links">
                                    <ul class="d-flex justify-content-start">
                                        <li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-twitter-filled"></i></a></li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a></li>
                                        <li><a href="javascript:void(0)"><i class="lni lni-instagram-filled"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= blog-section end ========================= -->

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
