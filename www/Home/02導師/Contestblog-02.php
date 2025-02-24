<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.html");
    exit();
}

$userData = $_SESSION['user'];

// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 例如從 SESSION 中獲取 user_id

$query = sprintf("SELECT * FROM user WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);

if (!isset($_SESSION['user'])) {
    echo("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>比賽資訊</title>
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
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.css" rel="stylesheet" />
       
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.js"></script>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.2.0/fullcalendar.min.js"></script>
    <script>

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });

    </script>
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="styles.css">
        <style>
    /* Inline CSS for simplicity */
    .portfolio-section {
        padding-top: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .portfolio-item-wrapper {
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
        background-color: #fff;
        padding: 10px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease;
        max-width: 600px;  /* 控制卡片寬度 */
        margin: 10px auto; /* 讓卡片置中，每個卡片之間有間距 */
    }

    .portfolio-item-wrapper:hover {
        transform: translateY(-3px);
    }

    .portfolio-img img {
        width: 100%;  /* 縮小圖片寬度 */
        height: auto;
        border-radius: 4px;
    }

    .portfolio-content {
        text-align: center;  /* 調整內容置中 */
        margin-top: 10px;
    }

    .portfolio-content h5 {
        font-size: 1.2rem;  /* 調小標題字體大小 */
        font-weight: 600;
        margin-bottom: 8px;
    }

    .portfolio-content .small-text {
        font-size: 0.9rem;  /* 調小描述文字大小 */
        color: #555;
        line-height: 1.4;
        margin-bottom: 10px;
    }

    .theme-btn {
        font-size: 0.85rem;  /* 按鈕字體變小 */
        padding: 6px 12px;   /* 調整按鈕的內邊距 */
        color: #fff;
        background-color: #007bff;
        border-radius: 4px;
        display: inline-block;
        transition: background-color 0.3s ease;
        text-decoration: none;
    }

    .theme-btn:hover {
        background-color: #0056b3;
    }
</style>


</head>
<?php
// 資料庫連接設置
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 設定時區
date_default_timezone_set('Asia/Taipei');
$currentDate = date('Y-m-d');  // 獲取當前日期
// 查詢未過期的比賽資訊
$sql = "SELECT name, inform, link, image FROM information WHERE display_end_time >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

// 檢查是否有資料
$competitions = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $competitions[] = $row;
    }
} else {
    echo "目前沒有未過期的比賽資訊。";
}
$conn->close();
?>
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
        <div class="row justify-content-center">
            <!-- 左側主要內容區 -->
            <div class="col-xl-8 col-lg-7">
                <div class="left-side-wrapper">
                    <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                        
                        <!-- 將搜尋表單放在資料上方 -->
                        <div class="text-center mb-4">
                            <form action="Contestsearch1.php" method="GET" class="search-form d-inline-block">
                                <input type="text" placeholder="Search..." name="keyword" required>
                                <button type="submit"><i class="lni lni-search-alt"></i>搜尋</button>
                            </form>
                        </div>

                        <section class="portfolio-section pt-130">
                            <div class="container">
                                <div class="row justify-content-center">
                                    <!-- 這裡是 PHP 迴圈呈現 competitions 資料 -->
                                    <?php foreach ($competitions as $competition): ?>
                                        <div class="col-12 mb-4">
                                            <div class="portfolio-item-wrapper">
                                                <div class="portfolio-content mt-2">
                                                    <h5><?= htmlspecialchars($competition['name']) ?></h5>
                                                    <a href="<?= htmlspecialchars($competition['link']) ?>" class="theme-btn border-btn" target="_blank">查看詳細資料</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>

           
        </div>
    </div>
</section>
        <!-- ========================= blog-section end ========================= -->

      

        <!-- ========================= footer start ========================= -->
        <footer class="footer pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                        <a href="index-03.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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