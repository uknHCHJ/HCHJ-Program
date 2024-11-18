<?php
session_start();
include 'db.php';
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);
//if (mysqli_num_rows($result) > 0) {
// $userDetails = mysqli_fetch_assoc($result);  
//} else {
// echo "找不到使用者的詳細資料";
//}
?>
<!Doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>二技校園介紹網</title>
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
                        <a class="nav-item dd-menu">查看個人資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/changepassword-01.html">修改密碼</a></li>
                                    <li class="nav-item active"><a href="/~HCHJ/Home/contact-01(個人資料).php">個人資料</a></li>                                </ul> 
                            </li>
                            <li class="nav-item">
                            <a class="nav-item dd-menu">備審資料</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/Home/recordforreview-01(備審紀錄).php">備審紀錄</a></li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/messageboard-01(留言板).php ">導師留言板</a></li>
                                </ul> 
                            </li>
                            <li class="nav-item"> 
                                <a class="page-scroll" href="/~HCHJ/Home/blog-01(比賽資訊).php">比賽資訊</a>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="/~HCHJ/Home/Contest-history(學生).php">競賽紀錄</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-item dd-menu">志願序</a>
                                <ul class="sub-menu">
                                    <li class="nav-item active"><a href="/~HCHJ/Home/optional(填選志願1)-01.php">選填志願</a></li>
                                    <li class="nav-item active"><a href="/~HCHJ/Home/optional(志願顯示).php">編輯</a></li>
                                </ul> 
                            </li>
                            <li class="nav-item">
                            <a class="page-scroll" >目前登入使用者：<?php echo $userId; ?></a>
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
                        <h2 class="text-white">二技校園網介紹</h2>
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

    <!-- ========================= portfolio-section start ========================= -->
    <section class="portfolio-section pt-130">
        <div id="container" class="container">
            <div class="row">
                <div class="col-12">
                    <div class="portfolio-btn-wrapper">
                        <button class="portfolio-btn active" data-filter="*">全部</button>
                        <button class="portfolio-btn" data-filter=".north">北部</button>
                        <button class="portfolio-btn" data-filter=".central">中部</button>
                        <button class="portfolio-btn" data-filter=".South">南部</button>
                    </div>
                </div>
            </div>
            <div class="row grid">
                <div class="col-lg-4 col-md-10 grid-item central web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/NUTC(二技).gif" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立臺中科技大學</h4>
                                <p>以「落實創新服務」及「培育立型人才」為理念。
                                    現有三大學制：日間部、進修部、空中學院。
                                    日間部包括研究所、四技、二技、五專。
                                    進修部包括二技、四技、二專。</p>
                                <a href="https://www.nutc.edu.tw/?Lang=zh-tw" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-10 grid-item central web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/YunTech(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立雲林科技大學</h4>
                                <p>現有工程、管理、設計、人文與科學、未來等5個學院，
                                    四技日間部24系（含5個學位學程）、
                                    二技日間部2系、四技進修部2個學位學程、
                                    碩士日間部28所（含1個學位學程）、碩士在職專班15所（含1個學位學程）、
                                    博士班13所（含1個學位學程），學生約一萬人</p>
                                <a href="https://www.yuntech.edu.tw/index.php" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 grid-item central web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/NHUST(二技).jpeg" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立虎尾科技大學</h4>
                                <p>培育具備「人際互動、自我成長、人文素養、國際移動、創新創意、跨域整合、資訊能力、專業技能」
                                    之國家社會亟需之實務專業人才，以促進產學共同發展、厚植國家競爭力。</p>
                                <a href="https://www.nfu.edu.tw/zh/" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/NTUB(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立台北商業大學</h4>
                                <p>現設有研究所（博士班、碩士班）、日間學制（四技、二技及五專）、
                                    進修學制（四技、二技及二專）、二專進修部（於109年8月1日由原附設專科進修學校改制成立）、
                                    附設空中進修學院。（原附設高商進修學校於105年6月學制結束）</p>
                                <a href="https://www.ntub.edu.tw/index.php" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/NTCN(二技).jpg" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立台北護理健康大學</h4>
                                <p>結合健康照護實務運用之「專業實務能力鑑定中心」
                                    及研發處結合業界產學合作與應用之「健康照護產學營運中心」及「育成中心」
                                    等創新特色行政單位支援，成為培育國家社會醫療照護專業人才的堅強後盾。</p>
                                <a href="https://www.ntunhs.edu.tw/?Lang=zh-tw" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 grid-item South web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/NKUST(二技).jpeg" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>國立高雄科技大學</h4>
                                <p>「建工校區」位處高雄市三民區建工商圈，地點極佳、生活機能便利，設有工學院及電資學院。
                                    「燕巢校區」依山傍水，校園生態豐富，設有管理學院及人文社會學院。</p>
                                <a href="https://www.nkust.edu.tw/index.php" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/CLUT(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>致理科技大學</h4>
                                <p>104年8月奉准改名為科技大學。 本校校名「致理」，
                                    取法《大學》致知明理，及基督教服膺真理之義，
                                    實融合中西聖哲修身濟世之精神，亦符合創校諸君子興學之理念；
                                    而校訓「誠信精勤」，則期望致理人以誠信立身，精勤成己，終身奉行不渝。</p>
                                <a href="https://www.chihlee.edu.tw/index.php" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/LHU(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>龍華科技大學</h4>
                                <p>設觀光休閒系及文化創意與數位媒體設計系、
                                    化工與材料工程系碩士班、
                                    多媒體與遊戲發展科學系碩士班
                                    、國際觀光與會展碩士班、資訊網路工程系碩士班</p>
                                <a href="https://www.lhu.edu.tw/?Lang=zh-tw" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/HDUT(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>宏國德霖科技大學</h4>
                                <p>日間部和進修學制現有二技、四技、二專及五專等學制，並開設產學合作專班
                                    。系科方面，有工程學院、不動產學院、餐旅學院，總計3個學院、15個學系</p>
                                <a href="https://www.hdut.edu.tw/index.html" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/TCMT(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>台北海洋科技大學</h4>
                                <p>本校以培育海事與商業之專業技術人才為宗旨之技職院校，
                                    專業、忠誠、勤奮、堅毅、服務為本校辦學理念，</p>
                                <a href="https://www.tumt.edu.tw/app/home.php" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 grid-item north web">
                    <div class="portfolio-item-wrapper">
                        <div class="portfolio-img">
                            <img src="schoolimages/TMUST(二技).png" alt="">
                        </div>
                        <div class="portfolio-overlay">
                            <div class="overlay-content">
                                <h4>德明財經科技大學</h4>
                                <p>為國內第一所財經科技大學，
                                    目前設有財金學院、管理學院、資訊學院等三個學院及所屬十二個系(含二個碩士班)。</p>
                                <a href="https://www.takming.edu.tw/schtm/default.asp" class="theme-btn border-btn">查看詳細資料</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= portfolio-section end ========================= -->

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

    <script>
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