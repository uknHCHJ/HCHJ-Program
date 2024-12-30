<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user']; //

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username=$userData['name']; 
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>匯出檔案</title>
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
                            <a class="navbar-brand" href="index-00.php">
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
                                        <a href="index-00.php">首頁</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="contact-00.php">個人資料</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../changepassword.html">修改密碼</a>
                                    </li>  
                                    <li class="nav-item">
                                        <a href="Adduser.php">新增人員</a>
                                    </li>        
                                    <li class="nav-item">
                                        <a href="Access-Control1.php">權限管理</a>                                
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" >目前登入使用者：<?php echo $userId; ?></a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="/~HCHJ/Permission.php" >切換使用者</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll" href="../logout.php" >登出</a>
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
                            <h2 class="text-white">匯出檔案</h2>
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
                            <span class="wow fadeInDown" data-wow-delay=".2s">選擇需要匯出的檔案</span>
                        </div>
                    </div>
                </div>

                <div class="table-container">
                <form id="export-form" action="export-file2.php" method="POST">
                    <div class="form-group mb-3">
                        <label for="user">匯出帳號：</label>
                        <input type="text" id="user" name="user" class="form-control" value="<?php echo isset($_SESSION['user']['user']) ? $_SESSION['user']['user'] : ''; ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="export-option" class="checkbox-label">選擇匯出檔案類型：</label><br>
                        <div class="checkbox-container">
                            <input type="checkbox" id="option-all" name="options[]" value="all" class="custom-checkbox">
                            <label for="option-all" class="checkbox-label">全部</label>

                            <input type="checkbox" id="option-license" name="options[]" value="license" class="custom-checkbox">
                            <label for="option-license" class="checkbox-label">證照資料</label>

                            <input type="checkbox" id="option-competition" name="options[]" value="competition" class="custom-checkbox">
                            <label for="option-competition" class="checkbox-label">競賽資料</label>
                        </div>
                    </div>

                <style>
                    /* 方形選項顯示在一排 */
                    .checkbox-container {
                        display: flex;
                        gap: 15px; /* 設定選項之間的間距 */
                        flex-wrap: wrap; /* 如果空間不足，換行顯示 */
                    }

                    .custom-checkbox {
                        width: 20px;
                        height: 20px;
                        vertical-align: middle;
                    }

                    .checkbox-label {
                        font-size: 16px;
                        color: #333;
                        margin-left: 5px;
                        vertical-align: middle;
                    }

                    /* 強調選項 */
                    .custom-checkbox:checked + .checkbox-label {
                        font-weight: bold;
                        color: #007bff;
                    }

                    /* 美化方形選項的標籤 */
                    .checkbox-label:hover {
                        color: #007bff;
                        cursor: pointer;
                    }
                </style>

                    <button type="submit" class="btn btn-primary">匯出檔案(.docx)</button>
                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">返回上一頁</button>
                    </form>
                </div>
            </div>
        </section>

 <!-- ========================= footer start ========================= -->
        <footer class="footer pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                            <a href="index-00.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
                var username = urlParams.get('username');
                var currentPermission = urlParams.get('permission');

                // 將參數填入表單
                if (username) {
                    document.getElementById('user').value = username;
                }
                if (currentPermission) {
                    document.getElementById('current-permission').value = currentPermission;
                }
            });
        </script>
    </body>
</html>
