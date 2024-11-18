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
                                        <li class="nav-item"><a href="portfolio-03(二技校園網介紹).php">二技校園介紹網</a></li>
                                    </li>    
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu">校園/科系</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="portfolio create-03(新增).php">新增校園</a></li>
                                        <li class="nav-item"><a href="portfolio delete-03(編輯).php">編輯詳細資料</a></li>
                                        </ul>
                                    </li>        
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu" >比賽資訊</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="blog-03(競賽).php">比賽資訊</a></li>
                                            <li class="nav-item"><a href="create-03.php">新增</a></li>
                                            <li class="nav-item"><a href="delete-03.php">編輯比賽資訊</a></li>
                                        </ul>
                                    </li>              
                                    <li class="nav-item">
                                        <a class="page-scroll" >目前登入使用者：<?php echo $userId; ?></a>
                                    </li>              
                                    <li class="nav-item">
                                        <a class="page-scroll" href="/~HCHJ/Permission.php" >切換使用者</a>
                                    </li>                                                                                                   
                            </div> <!-- navbar collapse -->
                        </nav> <!-- navbar -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        
        </header>
<!-- ========================= header end ========================= -->
<?php
$servername = "127.0.0.1"; // 伺服器IP或localhost
$username = "HCHJ"; // 資料庫登入帳號
$password = "xx435kKHq"; // 資料庫密碼
$dbname = "HCHJ"; // 資料庫名稱

// 建立與資料庫的連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
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

<!-- ========================= portfolio-section start ========================= -->
<section class="portfolio-section pt-130">
    <div id="container" class="container">
        <div class="row">
            <div class="col-12">
                <div class="portfolio-btn-wrapper">
                    <button class="portfolio-btn active" data-filter="*">全部</button>
                    <button class="portfolio-btn" data-filter=".north">北部</button>
                    <button class="portfolio-btn" data-filter=".central">中部</button>
                    <button class="portfolio-btn" data-filter=".south">南部</button>
                    <button class="portfolio-btn" data-filter=".east">東部</button>
                    <button class="portfolio-btn" data-filter=".islands">離島</button>
                    <div class="row grid">
    <?php
    // 顯示每筆學校資料
    foreach ($schools as $school) {
        // 取得地區並轉成小寫進行判斷
        $location = strtolower($school["location"]);
        
        // 設置地區分類
        switch ($location) {
            case "北部":
                $location_class = "north";
                break;
            case "中部":
                $location_class = "central";
                break;
            case "南部":
                $location_class = "south";
                break;
            case "東部":
                $location_class = "east";
                break;
            case "離島":
                $location_class = "islands";
                break;
            default:
                $location_class = "unknown"; // 可選的預設值
                break;
        }

        // 顯示學校圖片及資訊
        echo "<div class='col-lg-4 col-md-10 grid-item $location_class'>";
        echo "    <div class='portfolio-item-wrapper'>";
        echo "        <div class='portfolio-img'>";
        // 動態顯示圖片的 URL，指向顯示圖片的 PHP 文件
        echo "            <img src='dbportfolio_image2.php?id=" . $school['school_id'] . "' alt='" . htmlspecialchars($school["school_name"]) . "'>";
        echo "        </div>";
        echo "        <div class='portfolio-overlay'>";
        echo "            <div class='overlay-content'>";
        echo "                <h4>" . htmlspecialchars($school["school_name"]) . "</h4>";
        echo "                <p>" . htmlspecialchars($school["inform"]) . "</p>";
        echo "                <a href='" . htmlspecialchars($school["link"]) . "' class='theme-btn border-btn' target='_blank'>查看詳細資料</a>";
        echo "<a href='portfolio student-03(顯示科系) .php?school_id=" . htmlspecialchars($school['school_id']) . "' class='theme-btn border-btn' target='_blank'>二技科系</a>";
        echo "            </div>";
        echo "        </div>";
        echo "    </div>";
        echo "</div>";
    }
    ?>
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