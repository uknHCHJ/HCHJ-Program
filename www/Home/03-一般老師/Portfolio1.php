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
        <title>備審素材區</title>
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
        max-width: 500px; /* 設定最大寬度 */
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
        max-width: 500px; /* 設定欄位最大寬度 */
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
    <?php
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱


//建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

//確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}
?>

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
                                            <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
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
                            <h2 class="text-white">備審資料管理系統</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item" aria-current="page"><a href="index-03.php">首頁</a></li>
                                      </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- ========================= page-banner-section end ========================= -->
<div style="text-align: center; margin: auto;">
    <h1>備審資料管理系統</h1>
    <!-- 使用隱藏欄位將 student_id 傳入 -->
    <form action="PortfolioCreat.php" method="post" enctype="multipart/form-data" style="display: inline-block; text-align: center;">
        <input type="hidden" name="student_id" value="<?php echo $userId; ?>">

        <div style="margin-bottom: 15px;">
            <label for="category">選擇資料類型：</label>
            <select name="category" id="category" required style="text-align: center;">
                <option value="成績單">成績單</option>
                <option value="自傳">自傳</option>
                <option value="學歷證明">學歷證明</option>
                <option value="競賽證明">競賽證明</option>
                <option value="實習證明">實習證明</option>
                <option value="相關證照">相關證照</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label for="file">上傳檔案：</label>
            <input type="file" name="file" id="file" required style="text-align: center;">
        </div>
        <button type="submit" style="display: block; margin: 0 auto; background-color: blue; color: white; border: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; cursor: pointer;">
            上傳
        </button>
    </form>
</div>
<div class="portfolio-section pt-130">
    <div id="container" class="container">
        <div class="row">
            <div class="col-12">
                <div class="portfolio-btn-wrapper">
                    <button class="portfolio-btn active" data-filter="*">全部</button>
                    <button class="portfolio-btn" data-filter=".transcripts">成績單</button>
                    <button class="portfolio-btn" data-filter=".autobiographies">自傳</button>
                    <button class="portfolio-btn" data-filter=".certificates">學歷證明</button>
                    <button class="portfolio-btn" data-filter=".competitions">競賽證明</button>
                    <button class="portfolio-btn" data-filter=".internships">實習證明</button>
                    <button class="portfolio-btn" data-filter=".licenses">相關證照</button>
                </div>
                <div class="row grid">
                    <?php
                    // 資料庫連線設定
                    $servername = "127.0.0.1"; //伺服器IP或本地端localhost
                    $username = "HCHJ"; //登入帳號
                    $password = "xx435kKHq"; //密碼
                    $dbname = "HCHJ"; //資料庫名稱

                    // 建立連線
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // 確認連線是否成功
                    if ($conn->connect_error) {
                        die("連線失敗：" . $conn->connect_error);
                    }

                    // 確認是否有提供 student_id，優先從 POST 獲取，否則使用 Session 中的值
                    $student_id = isset($_POST['student_id']) ? intval($_POST['student_id']) : $userId;

                    // 查詢該學生的資料
                    $sql = "SELECT * FROM portfolio WHERE student_id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $student_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    // 檢查是否有資料
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // 根據資料類型決定分類類別
                            switch ($row["category"]) {
                                case "成績單":
                                    $category_class = "transcripts";
                                    break;
                                case "自傳":
                                    $category_class = "autobiographies";
                                    break;
                                case "學歷證明":
                                    $category_class = "certificates";
                                    break;
                                case "競賽證明":
                                    $category_class = "competitions";
                                    break;
                                case "實習證明":
                                    $category_class = "internships";
                                    break;
                                case "相關證照":
                                    $category_class = "licenses";
                                    break;
                                default:
                                    $category_class = "unknown";
                                    break;
                            }

                            // 輸出每筆資料
                            echo "<div class='col-lg-4 col-md-6 portfolio-item {$category_class}'>
                                <div class='portfolio-content'>
                                    <h3>{$row['category']}</h3>
                                    <p><a href='PortfolioDownload.php?id={$row['id']}'>{$row['file_name']}</a></p>
                                    <p>上傳時間：{$row['upload_time']}</p>
                                    <form action='PortfolioDelete.php' method='post'>
                                        <input type='hidden' name='id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                        <button type='submit' 
                                                onclick='return confirm(\"確定要刪除這筆資料嗎？\")' 
                                                style='background-color: red; 
                                                       color: white; 
                                                       border: none; 
                                                       padding: 12px 24px; 
                                                       font-size: 16px; 
                                                       border-radius: 8px; 
                                                       cursor: pointer; 
                                                       margin-top: 5px;
                                                       box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);'>
                                            刪除
                                        </button>
                                    </form>
                                </div>
                            </div>";
                        }
                    } else {
                        echo "<div class='col-12'><p>尚無資料</p></div>";
                    }

                    $stmt->close();
                    $conn->close();
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    </div>
    </div>
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
