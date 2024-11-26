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

$userData = $_SESSION['user'];

// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 例如從 SESSION 中獲取 user_id

$query = sprintf("SELECT * FROM user WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);


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

        <!-- 引入 FullCalendar 的 JavaScript -->
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction/main.js"></script>
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

// 查詢比賽資訊
if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $sql = "SELECT * FROM information WHERE name LIKE '%$keyword%' OR inform LIKE '%$keyword%'";
    $result = $conn->query($sql);
} else {
    echo "請返回並輸入關鍵字來搜尋。";
    exit;
}

date_default_timezone_set('Asia/Taipei');

// 取得當前月份和年份
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
                            <a class="page-scroll" href="index-01.php" >首頁</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-item dd-menu">個人資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item"><a href="/~HCHJ/Home/contact01-1.php">查看個人資料</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/changepassword.html">修改密碼</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-item dd-menu">備審資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item"><a href="/~HCHJ/Home/recordforreview01-1.php">備審紀錄</a>
                                    </li>

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
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_write1.php">選填志願</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_show1.php">查看志願序</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
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
                    <div class="single-blog blog-style-2 mb-60 wow fadeInUp" data-wow-delay=".1s">
                            <section class="portfolio-section pt-130">
                                <div class="container">
                                    <div class="row">
                                    <h1>搜尋結果</h1><br>

            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div style="border:1px solid #ddd; padding: 10px; margin: 10px 0;">
                        <h3><?= htmlspecialchars($row['name']) ?></h3><br>
                        <p><?= htmlspecialchars($row['inform']) ?></p><br>
                        <div>
                            <img src="data:image/jpeg;base64,<?= base64_encode($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="width:200px; height:auto;">
                        </div><br>
                        <a href="<?= htmlspecialchars($row['link']) ?>" class="theme-btn border-btn" target="_blank">查看詳細資料</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>找不到相關結果。</p>
            <?php endif; ?>

            <!-- 返回搜尋頁面的按鈕 -->
            <div style="margin-top: 20px;">
                <a href="Contestblog-01.php"><button style="padding: 10px 15px; font-size: 16px;" class="btn btn-secondary" >返回搜尋頁面</button></a>
            </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>
                            </div>
                        

                        <!-- Sidebar -->
                        <div class="col-xl-4 col-lg-5">
                    <!-- 最近公告 -->
                    <div class="sidebar-box recent-blog-box mb-30">
                        <h4>新公告</h4>
                        <!-- 月份導航 -->
                        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>">上一月</a> |
                        <span><?= $year ?>年 <?= $month ?>月</span> |
                        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>">下一月</a>
                        
                        <div class="recent-blog-items">
                            <div class="recent-blog mb-30">
                                <!-- 在這裡嵌入小月曆 -->
                                <style>
                                    table {
                                        width: 100%;
                                        border-collapse: collapse;
                                    }
                                    th, td {
                                        padding: 10px;
                                        text-align: center;
                                        border: 1px solid #ddd;
                                    }
                                    th {
                                        background-color: #f2f2f2;
                                    }
                                    .month-nav {
                                        text-align: center;
                                        margin-bottom: 20px;
                                    }
                                </style>

                                <!-- 顯示日曆表格 -->
                                <table>
                                    <thead>
                                        <tr>
                                            <th>日</th>
                                            <th>一</th>
                                            <th>二</th>
                                            <th>三</th>
                                            <th>四</th>
                                            <th>五</th>
                                            <th>六</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // 設置當前顯示的日期為1
                                        $currentDay = 1;
                                        // 循環顯示最多6行（如果有6行，則表示本月有6周）
                                        for ($row = 0; $row < 6; $row++) {
                                            echo "<tr>";  // 每行開頭
                                            
                                            // 顯示一週中的七天
                                            for ($col = 0; $col < 7; $col++) {
                                                // 當前行是否為該月的第一行且日期還沒到第一個日曆日期
                                                // 或當前日期大於當月總天數
                                                if (($row == 0 && $col < $firstDayOfWeek) || $currentDay > $totalDaysInMonth) {
                                                    echo "<td></td>";  // 空白格子（這些格子不顯示日期）
                                                } else {
                                                    // 顯示當前的日期
                                                    echo "<td>" . $currentDay . "</td>";
                                                    // 增加日期
                                                    $currentDay++;
                                                }
                                            }
                                            echo "</tr>";  // 每行結束
                                            // 當天數超過總天數時停止顯示
                                            if ($currentDay > $totalDaysInMonth) break;
                                        }
                                        ?>
                                    </tbody>
                                </table>
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
                        <a href="index-01.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
