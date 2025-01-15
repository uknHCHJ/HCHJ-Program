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
    
        <link rel="stylesheet" href="assets/css/main.css">
        <link rel="stylesheet" href="styles.css">
        <style>
    table {
        width: 80%;            /* 設定表格寬度，保持 80% 寬度 */
        max-width: 900px;      /* 最大寬度為 900px */
        margin: 20px auto;     /* 自動水平居中，並設定上下邊距 */
        border-collapse: collapse; /* 合併表格邊框 */
    }

    th, td {
        padding: 12px;         /* 單元格內邊距 */
        text-align: left;      /* 文字對齊左側 */
        border: 1px solid #ddd; /* 單元格邊框顏色 */
    }

    th {
        background-color: #f2f2f2; /* 表頭背景顏色 */
        font-weight: bold;         /* 設置表頭文字加粗 */
    }

    tr:hover {
        background-color: #f1f1f1; /* 滑鼠懸停時，行的背景顏色 */
    }

    td a {
        color: #007bff;          /* 設定連結顏色 */
        text-decoration: none;   /* 去除連結底線 */
    }

    td a:hover {
        text-decoration: underline; /* 滑鼠懸停時顯示底線 */
    }

    /* 使標題與表格更接近，減少與上方框框的距離 */
    .col-12.text-center.mb-4 h2 {
        margin-top: 0;   /* 取消標題與上方的空間 */
        margin-bottom: 10px;  /* 減少標題下方的空間 */
    }

</style>


</head>
<?php
// 連接到 MySQL 資料庫
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ"; // 請換成您的資料庫名稱

// 創建連接
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連接是否成功
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 查詢資料庫中的比賽資料（不檢查結束時間）
$sql = "SELECT name, link FROM information";
$result = $conn->query($sql);

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
                                        <a class="page-scroll" >目前登入使用者：<?php echo $userId; ?></a>
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
                                    <div class="col-12 text-center mb-4">
                                        <h2>資管科比賽資訊</h2>
                                    </div>

                                    <?php
                                    // 如果有比賽資料
                                    if ($result->num_rows > 0) {
                                        echo '<div style="display: flex; justify-content: center; overflow-x: auto;">';
                                        echo '<table style="width: 90%; max-width: 800px; border-collapse: collapse; text-align: left; margin: 0 auto;">';
                                        echo '<thead><tr><th style="padding: 12px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">比賽名稱</th><th style="padding: 12px; text-align: left; border: 1px solid #ddd; background-color: #f2f2f2;">連結</th></tr></thead>';
                                        echo '<tbody>';

                                        // 顯示每個比賽的名稱和連結
                                        while($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td style="padding: 12px; border: 1px solid #ddd;">' . htmlspecialchars($row['name']) . '</td>';
                                            echo '<td style="padding: 12px; border: 1px solid #ddd;"><a href="' . htmlspecialchars($row['link']) . '" target="_blank">點擊參賽</a></td>';
                                            echo '</tr>';
                                        }

                                        echo '</tbody>';
                                        echo '</table>';
                                        echo '</div>';
                                    } else {
                                        echo "<p>目前沒有任何比賽資訊。</p>";
                                    }

                                    // 關閉資料庫連接
                                    $conn->close();
                                    ?>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


            <!-- Sidebar -->
            <div class="col-xl-4 col-lg-5">
    <div class="sidebar-wrapper">
        
        <style>
            #calendar {
                max-width: 100%;   /* 設定為最大寬度，這樣它會根據容器大小自動調整 */
                width: 100%;       /* 設定為 100%，使其自動適應容器寬度 */
                height: 500px;     /* 設定固定高度，也可以根據需求進行調整 */
                margin: 0 auto;    /* 使日曆水平居中 */
            }
        </style>
            <!-- 當月日曆 -->
           
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
                        <a href="index-03.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
