<?php 
session_start();
include 'db.php';

// 確認使用者是否已登入
if (!isset($_SESSION['user'])) {
    echo "<script>
            alert('請先登入！');
            window.location.href = '/~HCHJ/index.html';
          </script>";
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user']; // 用戶識別符（假設使用 username 作為唯一識別符）

// 資料庫連接
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

mysqli_query($link, 'SET NAMES UTF8');

// 獲取 Session 資料
$username = $_SESSION['user']['name']; // 用戶名稱
$user = $_SESSION['user']['user']; // 用戶名稱
// 獲取當前用戶的權限
$query = "SELECT Permissions FROM user WHERE user = '$userId'";
$result = mysqli_query($link, $query);
$userRole = mysqli_fetch_assoc($result)['permissions'];

// 當前頁面處理留言
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']); // 留言內容

    if (!empty($message)) {
        // 如果用戶的權限是 2，將留言插入到所有留言板（包括權限為 1 的留言板）
        if ($userRole == 2) {
            $query = "INSERT INTO message (user, message, permissions) VALUES ('$user', '$message', 1)"; // 顯示在權限 1 的留言板
        } else {
            // 權限為 1 的用戶，只能將留言插入自己的留言板
            $query = "INSERT INTO message (user, message, permissions) VALUES ('$user', '$message', 1)"; // 或者將 1 替換成用戶權限值
        }

        if (mysqli_query($link, $query)) {
            echo "<script>window.location.reload();</script>";  // 留言後刷新頁面顯示新留言
        } else {
            echo "<script>alert('留言失敗！請再試一次。');</script>";
        }
    } else {
        echo "<script>alert('留言內容不能為空！');</script>";
    }
}

// 查詢留言列表
if ($userRole == 1) {
    // 權限為 1 的用戶只能查看自己的留言
    $query = "SELECT * FROM message WHERE permissions = 1 ORDER BY id DESC";
} elseif ($userRole == 2) {
    // 權限為 2 的用戶可以查看所有留言，包括權限為 1 的留言板上的留言
    $query = "SELECT * FROM message ORDER BY id DESC";
}

$result = mysqli_query($link, $query);
$comments = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>留言板</title>
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
        <style>
    /* 設定容器和表單樣式 */
    .form-container {
        text-align: center;
        width: 100%;
        max-width: 800px; /* 設定最大寬度 */
        margin: 0 auto;
        padding: 20px;
    }

    /* 調整標籤樣式 */
    label {
        display: block;
        text-align: left;
        font-weight: bold;
        font-size: 1.2em; /* 增加字型大小 */
        margin-top: 10px;
    }

    /* 設定 select、input 和 textarea 的樣式與大小 */
    select, input[type="text"], textarea, input[type="file"], input[type="date"] {
        width: 100%;
        max-width: 800px; /* 設定欄位最大寬度 */
        margin-top: 10px;
        padding: 8px;
        font-size: 1em;
        border: 1px solid #ced4da;
        border-radius: 5px;
    }

    /* 設定按鈕樣式 */
    button {
        font-size: 1.2em; /* 增加按鈕字型大小 */
        padding: 10px 20px;
    }
</style>

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
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">個人資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="../changepassword-01(修改密碼).html">修改密碼</a>
                                        </li>
                                        <li class="nav-item"><a href="/~HCHJ/Home/contact-01(個人資料).php">查看個人資料</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">備審資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="/~HCHJ/Home/upload-01(上傳備審).php">上傳備審</a>
                                        </li>
                                        <li class="nav-item active"><a
                                                href="/~HCHJ/Home/recordforreview-01(備審紀錄).php">備審紀錄</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="\Personal page\html\index.html">比賽資訊</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/Contest-history(學生).php">競賽紀錄</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/optional(填選志願1)-01.php">志願序填寫</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                                </li>
                            </ul>
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
    <section class="client-logo-section pt-100 pb-130">
        <div class="container">
            <div class="client-logo-wrapper">
                <div class="client-logo-carousel d-flex align-items-center justify-content-between">
                    <div class="client-logo">
                        <img src="assets/img/client-logo/uideck-logo.svg" alt="">
                    </div>
                    <div class="client-logo">
                        <img src="assets/img/client-logo/pagebulb-logo.svg" alt="">
                    </div>
                    <div class="client-logo">
                        <img src="assets/img/client-logo/lineicons-logo.svg" alt="">
                    </div>
                    <div class="client-logo">
                        <img src="assets/img/client-logo/graygrids-logo.svg" alt="">
                    </div>
                    <div class="client-logo">
                        <img src="assets/img/client-logo/lineicons-logo.svg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= client-logo-section end ========================= -->

    <!-- ========================= subscribe-section start ========================= -->
    <section class="subscribe-section pt-70 pb-70 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-6 col-lg-6">
                    <div class="section-title mb-30">
                        <span class="text-white wow fadeInDown" data-wow-delay=".2s">Subscribe</span>
                        <h2 class="text-white mb-40 wow fadeInUp" data-wow-delay=".4s">Subscribe Our Newsletter</h2>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6">
                    <form action="#" class="subscribe-form wow fadeInRight" data-wow-delay=".4s">
                        <input type="text" name="subs-email" id="subs-email" placeholder="Your Email">
                        <button type="submit"><i class="lni lni-telegram-original"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= subscribe-section end ========================= -->

    <!-- ========================= footer start ========================= -->
    <footer class="footer pt-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                        <a href="index.html" class="logo mb-30"><img src="assets/img/logo/logo.svg" alt="logo"></a>
                        <p class="mb-30 footer-desc">We Crafted an awesome desig library that is robust and intuitive to
                            use. No matter you're building a business presentation websit.</p>
                    </div>
                </div>
                <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6">
                    <div class="footer-widget mb-60 wow fadeInUp" data-wow-delay=".4s">
                        <h4>Quick Link</h4>
                        <ul class="footer-links">
                            <li>
                                <a href="javascript:void(0)">Home</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">About Us</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Service</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Contact</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-widget mb-60 wow fadeInUp" data-wow-delay=".6s">
                        <h4>Service</h4>
                        <ul class="footer-links">
                            <li>
                                <a href="javascript:void(0)">Marketing</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Branding</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Web Design</a>
                            </li>
                            <li>
                                <a href="javascript:void(0)">Graphics Design</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-6">
                    <div class="footer-widget mb-60 wow fadeInRight" data-wow-delay=".8s">
                        <h4>Contact</h4>
                        <ul class="footer-contact">
                            <li>
                                <p>+00983467367234</p>
                            </li>
                            <li>
                                <p>yourmail@gmail.com</p>
                            </li>
                            <li>
                                <p>United State Of America
                                    *12 Street House</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="copyright-area">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="footer-social-links">
                            <ul class="d-flex">
                                <li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-twitter-filled"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a></li>
                                <li><a href="javascript:void(0)"><i class="lni lni-instagram-filled"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p class="wow fadeInUp" data-wow-delay=".3s"><a target="_blank" href="http://www.mobanwang.com/"
                                title="网页模板">网页模板</a></p>
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