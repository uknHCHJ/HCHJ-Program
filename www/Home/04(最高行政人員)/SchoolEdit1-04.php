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


$servername = "127.0.0.1"; 
$username = "HCHJ"; 
$password = "xx435kKHq"; 
$dbname = "HCHJ"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}

// SQL 查詢語句，用來獲取學校資訊
$sql = "SELECT school_id, school_name, location, inform, link FROM School";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // 將每筆資料放入資料陣列中
    $schools = array();
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
    $result->free();
} else {
    echo "<p>目前無學校資料顯示。</p>";
}

// 關閉資料庫連線
$conn->close();
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>編輯</title>
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
    </head>
    <body>

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
                                    <li class="nav-item"><a href="index-03.php">首頁</a></li>
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
                                            <li class="nav-item"><a href="AddCompetition1.php">新增</a></li>
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
                                    <a href="javascript:void(0)"  onclick="submitLogout()">登出</a>
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
                            <h2 class="text-white">編輯</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item" aria-current="page"><a href="index-03.php">首頁</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">二技校園網介紹</li><a href="portfolio-03(二技校園網介紹).php"></a></li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= page-banner-section end ========================= -->

       
      <!-- ========================= service-section start ========================= -->
      <body>
      <section id="service" class="service-section pt-10 pb-5"> 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-7 col-md-9">
                <div class="section-title text-center mb-30"> <!-- 調整 margin-bottom -->
                    <h2>二技學校</h2> <!-- 調整標題底部間距 -->
                    <!-- 分頁標籤導航 -->
                    <div class="tab-navigation" style="margin-bottom: 15px;"> <!-- 調整導航間距 -->
                        <ul class="nav nav-pills justify-content-center" id="schoolTabs" role="tablist">
                            <?php 
                            // 生成地區標籤
                            $regions = ["北部", "中部", "南部", "東部", "離島"];
                            foreach ($regions as $index => $region) : ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link <?= $index === 0 ? 'active' : '' ?>" 
                                       id="<?= strtolower($region) ?>-tab" 
                                       data-toggle="tab" 
                                       href="#<?= strtolower($region) ?>" 
                                       role="tab" 
                                       aria-controls="<?= strtolower($region) ?>" 
                                       aria-selected="<?= $index === 0 ? 'true' : 'false' ?>">
                                        <?= $region ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div> 
            </div>         
        </div>

        <!-- 分頁內容 -->
        <div class="tab-content" id="schoolTabsContent" style="margin-top: 10px;"> <!-- 調整分頁內容頂部間距 -->
            <?php 
            // Displaying schools by region
            foreach ($regions as $index => $region) :
                $location_class = strtolower($region); 
                $filtered_schools = array_filter($schools, function($school) use ($location_class) {
                    return strtolower($school['location']) === $location_class;
                });
            ?>
                <div class="tab-pane fade <?= $index === 0 ? 'show active' : '' ?>" 
                     id="<?= strtolower($region) ?>" 
                     role="tabpanel" 
                     aria-labelledby="<?= strtolower($region) ?>-tab">
                    <div class="row justify-content-center mt-3"> <!-- 調整分頁內容行的頂部間距 -->
                        <div class="col-md-8">
                            <table class="table table-hover text-center" style="font-size: 1.2em; line-height: 1.4;">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">學校名稱</th> 
                                        <th style="width: 30%;">內容</th> 
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($filtered_schools as $index => $school) : ?>
                                        <tr>
                                            <td><?= $school['school_name'] ?></td>
                                            <td>
                                                <a href="SchoolDepartment-04.php?school_id=<?= $school['school_id'] ?>" class="btn btn-info">科系</a>
                                                <a href="SchoolUpdate-04.php?school_id=<?= $school['school_id'] ?>" class="btn btn-success">修改</a>
                                                <a href="SchoolDelete2-04.php?school_id=<?= $school['school_id'] ?>" class="btn btn-danger" onclick="return confirm('確定要刪除該學校及其關聯科系資料嗎？')">刪除</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
        <!-- ========================= service-section end ========================= -->
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
