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
            margin: 10px auto; /* 讓卡片居中，每個卡片之間有間距 */
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
            text-align: left;
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

// 取得當前年份和月份
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$month = isset($_GET['month']) ? $_GET['month'] : date('m');

// 計算這個月的第一天是星期幾
$firstDayOfMonth = strtotime("$year-$month-01");
$firstDayOfWeek = date('w', $firstDayOfMonth); // 0 (星期天) 到 6 (星期六)

// 計算當月的總天數
$totalDaysInMonth = date('t', $firstDayOfMonth);

// 計算上一個月和下一個月
$prevMonth = date('m', strtotime("-1 month", strtotime("$year-$month-01")));
$prevYear = date('Y', strtotime("-1 month", strtotime("$year-$month-01")));
$nextMonth = date('m', strtotime("+1 month", strtotime("$year-$month-01")));
$nextYear = date('Y', strtotime("+1 month", strtotime("$year-$month-01")));

// 取得今天的日期
$today = date('Y-m-d');
// 關閉資料庫連線
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
                            <a class="navbar-brand" href="index-04.php">
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
                                        <a href="index-04.php">首頁</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">個人資料</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="contact-04.php">查看個人資料</a></li>
                                            <li class="nav-item"><a href="../changepassword.html">修改密碼</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">班級管理</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="Preparation1-04.php">查看學生備審資料</a></li>
                                            <li class="nav-item"><a href="order1.php">查看志願序</a></li>
                                            <li class="nav-item"><a href="Contest-history1.php">查看競賽歷程</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu">二技校園網</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Schoolnetwork1-04.php">首頁</a></li>
                                        <li class="nav-item"><a href="AddSchool1-04.php">新增校園</a></li>
                                        <li class="nav-item"><a href="SchoolEdit1-04.php">編輯資訊</a></li>                                        
                                        </ul>
                                    </li> 
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu" >比賽資訊</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog1-04.php">查看</a></li>
                                            <li class="nav-item"><a href="AddContest1-04.php">新增</a></li>
                                            <li class="nav-item"><a href="ContestEdin1-04.php">編輯</a></li>
                                        </ul>
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
            <!-- Blog Content -->
            <div class="col-xl-8 col-lg-7">
                <div class="left-side-wrapper">
                    <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".2s">
                            <section class="portfolio-section pt-130">
                                <div class="container">
                                    <div class="row">
                                        <?php foreach ($competitions as $competition): ?>
                                            <div class="col-12 mb-4">
                                                <div class="portfolio-item-wrapper">
                                                    <div class="portfolio-img">
                                                        <img src="data:image/jpeg;base64,<?= base64_encode($competition['image']) ?>" alt="<?= htmlspecialchars($competition['name']) ?>" class="img-fluid">
                                                    </div>
                                                    <div class="portfolio-content mt-2">
                                                        <h5><?= htmlspecialchars($competition['name']) ?></h5>
                                                        <p class="small-text"><?= htmlspecialchars($competition['inform']) ?></p>
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
            

            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
    <div class="sidebar-wrapper">
        <!-- 搜索表單 -->
        <div class="sidebar-box search-form-box mb-30">
            <form action="Contestsearch.php" method="GET" class="search-form">
            <input type="text" placeholder="Search..." name="keyword" required>
                <button type="submit"><i class="lni lni-search-alt"></i>搜尋</button>
            </form>
        </div>
        <style>
            #calendar {
                max-width: 100%;   /* 設定為最大寬度，這樣它會根據容器大小自動調整 */
                width: 100%;       /* 設定為 100%，使其自動適應容器寬度 */
                height: 500px;     /* 設定固定高度，也可以根據需求進行調整 */
                margin: 0 auto;    /* 使日曆水平居中 */
            }
        </style>
            <!-- 當月日曆 -->
            <div class="sidebar-box recent-blog-box mb-100">
            <div id="calendar"></div>
            <!-- 小月曆樣式 -->
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var calendarEl = document.getElementById('calendar');  // 選擇 id 為 calendar 的 div 元素

                    var calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',  // 設定預設視圖為月份視圖
                        locale: 'zh-tw',  // 設定語言為中文
                    });

                    calendar.render();  // 渲染日曆
                });
            </script>
                    </div>
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
                            <a href="index-04.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
