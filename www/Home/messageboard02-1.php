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
$username = $userData['name'];
// 資料庫連接
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

mysqli_query($link, 'SET NAMES UTF8');

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
    <style>
        /* 設定容器和表單樣式 */
        .form-container {
            text-align: center;
            width: 100%;
            max-width: 800px;
            /* 設定最大寬度 */
            margin: 0 auto;
            padding: 20px;
        }

        /* 調整標籤樣式 */
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            font-size: 1.2em;
            /* 增加字型大小 */
            margin-top: 10px;
        }

        /* 設定 select、input 和 textarea 的樣式與大小 */
        select,
        input[type="text"],
        textarea,
        input[type="file"],
        input[type="date"] {
            width: 100%;
            max-width: 800px;
            /* 設定欄位最大寬度 */
            margin-top: 10px;
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        /* 設定按鈕樣式 */
        button {
            font-size: 1.2em;
            /* 增加按鈕字型大小 */
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
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序統計</a></li>
                                    </ul>
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
                        <h2 class="text-white">留言板</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" aria-current="page"><a href="javascript:void(0)">首頁</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">留言板</li>
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
            <form action="messageboard2-01.php" method="post">
                <label for="message">新增留言：</label>
                <textarea id="message" name="message" class="form-control" rows="3" required></textarea><br>
                <button type="submit" class="btn btn-info">送出</button>
            </form>
        </div>
    </section>

    <section class="mt-4">
        <div class="message-list container">
            <h4>留言列表：</h4>
            <?php
            $userData = $_SESSION['user'];
            $grade = $userData['grade'];
            $class = $userData['class'];
            $currentUserId = $userData['id'];
            $permissions1 = explode(',', $userData['Permissions']);
            //登入使用者的權限
            $permissions1 = explode(',', $userData['Permissions']);
            // 使用 LIKE 查詢包含指定年級和班級的記錄 把對應的老師找出來
            $sql = "SELECT * FROM `user` WHERE `grade` LIKE '%$grade%' AND `class` LIKE '%$class%' AND `id` != $currentUserId";
            $result = mysqli_query($link, $sql);
            if ($result) {
                $teachers = [];  // 用於儲存符合條件的班導名字
                while ($row = mysqli_fetch_assoc($result)) {
                    $permissions2 = explode(',', $row['Permissions']);//放同班及其他使用者對應的權限
                    if (in_array('2', $permissions2)) {//權限是2就把名字印出來存入
                        $teachers[] = $row['name'];
                    }
                }
            } else {
                echo "查詢失敗：" . mysqli_error($link);
            }
            // 查詢所有留言
            $query = "SELECT * FROM message  WHERE `user` LIKE '%$teachers[0]%' ORDER BY id DESC";  // DESC 代表顯示最新的留言在最上面
            $result = mysqli_query($link, $query);

            // 檢查是否有留言
            if (mysqli_num_rows($result) > 0) {
                // 顯示留言
                while ($row = mysqli_fetch_assoc($result)) {

                    echo "<p><strong>" . htmlspecialchars($row['user']) . ":" . htmlspecialchars($row['message']) . "</strong></p>";
                }
            } else {
                echo "目前沒有留言。";
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
            max-width: 800px;
            /* 設定最大寬度 */
            width: 100%;
            /* 寬度自適應 */
            text-align: center;
        }

        /* 設定表單內的按鈕置中 */
        .alerts-section .btn-primary {
            display: block;
            margin: 0 auto;
        }

        /* 修改alert樣式 */
        .message-list .alert {
            margin-bottom: 20px;
            /* 每條留言卡片之間的間隔 */
            text-align: left;
            border-radius: 8px;
            /* 增加圓角 */
            padding: 15px;
            background-color: #e0f7fa;
            /* 背景顏色更柔和 */
            border: 1px solid #b2ebf2;
            /* 背景邊框 */
            width: 100%;
            /* 留言寬度自適應 */
            max-width: 800px;
            /* 最大寬度為800px */
            margin: 10px auto;
            /* 置中顯示 */
        }

        /* 設定alert文字樣式 */
        .message-list .alert a {
            font-size: 16px;
            text-decoration: none;
            color: #333;
        }

        /* 留言懸停效果 */
        .message-list .alert:hover {
            background-color: #b2ebf2;
            /* 滑鼠懸停時改變顏色 */
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

    <script>
        //========= glightbox
        GLightbox({
            'href': '#',
            'type': 'video',
            'source': 'youtube', //vimeo, youtube or local
            'width': 900,
            'autoplayVideos': true,
        });

        //========= testimonial 
        tns({
            container: '.testimonial-active',
            items: 1,
            slideBy: 'page',
            autoplay: false,
            mouseDrag: true,
            gutter: 0,
            nav: false,
            controlsText: ['<i class="lni lni-arrow-left"></i>', '<i class="lni lni-arrow-right"></i>'],
        });

        //============== isotope masonry js with imagesloaded
        imagesLoaded('#container', function () {
            var elem = document.querySelector('.grid');
            var iso = new Isotope(elem, {
                // options
                itemSelector: '.grid-item',
                masonry: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: '.grid-item'
                }
            });

            let filterButtons = document.querySelectorAll('.portfolio-btn-wrapper button');
            filterButtons.forEach(e =>
                e.addEventListener('click', () => {

                    let filterValue = event.target.getAttribute('data-filter');
                    iso.arrange({
                        filter: filterValue
                    });
                })
            );
        });
    </script>
</body>

</html>