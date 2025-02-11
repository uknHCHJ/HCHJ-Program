<!doctype html>
<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    echo "資料庫連接失敗: " . mysqli_connect_error();
    exit();
}

if (!isset($_SESSION['user'])) {
    echo("<script>
            alert('請先登入！！');
            window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user'];
// 從 SESSION 中取得使用者資訊
$username = $userData['name'];
$userId   = $userData['user'];
$grade    = $userData['grade'];
$class    = $userData['class'];
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>學生上傳狀態</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
        <!-- CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/tiny-slider.css">
        <link rel="stylesheet" href="assets/css/glightbox.min.css">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <!-- preloader start -->
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

        <!-- header start -->
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
                                        <a href="pay1.php">繳交紀錄</a>                                
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
                                </ul>                                    
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </header>
        <!-- header end -->

        <!-- page-banner-section start -->
        <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="banner-content">
                            <h2 class="text-white">學生檔案上傳狀態</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">學生檔案上傳狀態</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page-banner-section end -->

        <section class="container mt-5">
            <h2 class="text-center">檔案上傳狀態(成績單)</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>學號</th>
                        <th>上傳時間</th>
                        <th class='text-center'>狀態</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 從資料庫取得資料
                    $query = "SELECT student_id, file_content, upload_time FROM portfolio WHERE class='$class' AND category = '成績單'";
                    $result = mysqli_query($link, $query);
                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // 後端判斷：如果 categry 為空，則認定為「未上傳」，否則為「已上傳」
                            if (empty($row['file_content'])) {
                                $status = "❌";
                            } else {
                                $status = "✔️";
                            }
                            echo "<tr>
                            <td>{$row['student_id']}</td>
                            <td>{$row['upload_time']}</td>
                            <td class='text-center'>$status</td>
                          </tr>";
                    
                        }
                    } else {
                        echo "<tr><td colspan='3'>查詢失敗：" . mysqli_error($link) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="history.back()">返回上一頁</button>
            </div>
        </section>

        <!-- client-logo-section start -->
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
        <!-- client-logo-section end -->

        <!-- footer start -->
        <footer class="footer pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                            <a href="index-04.php" class="logo mb-30">
                                <img src="schoolimages/uknlogo.png" alt="logo">
                            </a>
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
                                    justify-content: space-between;
                                }
                                .footer-widget {                                   
                                    text-align: right;
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
        <!-- footer end -->

        <!-- scroll-top -->
        <a href="#" class="scroll-top">
            <i class="lni lni-arrow-up"></i>
        </a>
        
        <!-- JS -->
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
