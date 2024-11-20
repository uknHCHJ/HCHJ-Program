<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.php");
    exit();
}

$userData = $_SESSION['user']; 

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username=$userData['name']; 
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>新增競賽歷程資訊</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
        <!-- 引入CSS樣式 -->
        <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>

 <!-- ========================= header start ========================= -->
 <header class="header navbar-area">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="index-01.php">
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
                        <a class="nav-item dd-menu">個人資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/changepassword.html">修改密碼</a></li>
                                    <li class="nav-item active"><a href="/~HCHJ/Home/contact01-1.php">查看個人資料</a></li>                                </ul> 
                            </li>
                            <li class="nav-item">
                            <a class="nav-item dd-menu">備審資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/Home/recordforreview01-1.php">備審紀錄</a></li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/messageboard-01(留言板).php ">導師留言板(無使用)</a></li>
                                </ul> 
                            </li>
                            <li class="nav-item"> 
                                <a class="page-scroll" href="/~HCHJ/Home/Contestblog-01.php">比賽資訊</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="/~HCHJ/Home/Contest-history(學生).php">競賽紀錄</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-item dd-menu">志願序</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/Home/optional_write1.php">選填志願</a></li>
                                    <li class="nav-item active"><a href="/~HCHJ/Home/optional_show1.php">查看志願序</a></li>
                                </ul> 
                            </li>
                            <li class="nav-item">
                            <a class="page-scroll" >目前登入使用者：<?php echo $userId; ?></a>
                            </li>
                            <li class="nav-item">
                            <a class="page-scroll" href="/~HCHJ/logout.php">登出</a>
                          
                            </li>
                        </ul>
                    </div> <!-- navbar collapse -->
                </nav> <!-- navbar -->
            </div>
        </div> <!-- row -->
    </div> <!-- container -->

</header>
<!-- ========================= header end ========================= -->
        <!-- 標題區塊 -->
        <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="banner-content">
                            <h2 class="text-white">新增競賽歷程資訊</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">新增競賽歷程資訊</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 表單區塊 -->
        <section id="service" class="service-section pt-130 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                        <div class="section-title text-center mb-55">
                            <span class="wow fadeInDown" data-wow-delay=".2s">新增競賽歷程資訊</span>
                        </div>
                    </div>
                </div>

<div class="table-container">
    <form id="permission-form" action="upload-image.php" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="imageUpload">上傳圖片( .jpg)：</label>
            <input type="file" id="imageUpload" name="imageUpload" class="form-control" accept=".jpg">
        </div>
        <div class="form-group mb-3">
            <label for="contest-name">競賽名稱：</label>
            <input type="text" id="contest-name" name="contest-name" class="form-control">
        </div>
        <div class="form-group mb-3">
            <label for="user">學號：</label>
            <input type="text" id="user" name="user" class="form-control" readonly>
        </div>
        <div class="form-group mb-3">
            <label for="username">姓名：</label>
            <input type="text" id="username" name="username" class="form-control" readonly>
        </div>
        <button type="submit" class="btn btn-primary">確認上傳</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">返回上一頁</button>
    </form>

</div>
        </section>

 <!-- ========================= footer start ========================= -->
        <footer class="footer pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                            <a href="index-0.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
        
        <!-- 引入JS -->
        <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 解析 URL 中的參數
                var urlParams = new URLSearchParams(window.location.search);
                var user = urlParams.get('user');//從前面抓網址上面的回傳user
                var username = urlParams.get('name');//從前面抓網址上面的回傳的name

                // 將參數填入表單
                if (username && username) {
                    document.getElementById('user').value = user;
                    document.getElementById('username').value = username;
                }
            });
        </script>
    </body>
</html>
