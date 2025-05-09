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
        <title>自傳/讀書心得 填寫</title>
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
                                <a class="page-scroll" href="/~HCHJ/Home/Contestblog1-01.php">比賽資訊</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-item dd-menu">志願序</a>
                                <ul class="sub-menu">
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_write1.php">選填志願(技優)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_write2.php">選填志願(申請入學)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_show1.php">查看志願序(技優)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_show3-1.php">查看志願序(申請入學)</a>
                                    </li>
                                    </a>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-item dd-menu">備審管理區</a>
                                <ul class="sub-menu">
                                    <li class="nav-item"><a href="/~HCHJ/Home/Portfolio1.php">備審素材區</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/AutobiographyCreat1.php">自傳/讀書心得填寫</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/export-file1.php">匯出備審</a>
                                    </li>
                                    </a>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="page-scroll" href="/~HCHJ/Home/Schoolnetwork1-01.php">二技校園介紹網</a>
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
                            <h2 class="text-white">備審素材區</h2>
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
        <div style="text-align: center; margin: auto;">
    <h1>備審填寫</h1>
    <form action="AutobiographyCreat2.php" method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="return confirmUpload()" style="display: inline-block; text-align: center;">
        
        <label for="category">選擇類別：</label>
        <select id="category" name="category" onchange="toggleFields()">
            <option value="autobiography">自傳</option>
            <option value="studyPlan">讀書計畫</option>
        </select>
        
        <br><br>
        
        <div id="autobiographyFields">
            <label for="title">自傳名稱：</label>
            <input type="text" name="title" id="title">
            
            <br><br>
            
            <label for="content">自傳內容：</label>
            <textarea name="content" id="content" rows="10" cols="50"></textarea>
        </div>
        
        <div id="studyPlanFields" style="display: none;">
            <label for="planTitle">讀書計畫名稱：</label>
            <input type="text" name="planTitle" id="planTitle">
            
            <br><br>
            
            <label for="planContent">讀書計畫內容：</label>
            <textarea name="planContent" id="planContent" rows="10" cols="50"></textarea>
        </div>
        
        <br><br>
        
        <button type="submit" onclick="return confirm('確定要上傳此檔案嗎？')" 
            style="background-color: blue; color: white; border: none; padding: 10px 20px; 
            font-size: 16px; border-radius: 5px; cursor: pointer;">
            上傳
        </button>
    </form>
</div>

<!-- 前端 JavaScript -->
<script>
function toggleFields() {
    // 取得「類別」選擇的值
    const category = document.getElementById('category').value;
    // 根據選擇的類別顯示或隱藏對應的輸入欄位
    document.getElementById('autobiographyFields').style.display = category === 'autobiography' ? 'block' : 'none';
    document.getElementById('studyPlanFields').style.display = category === 'studyPlan' ? 'block' : 'none';
}

function confirmUpload() {
    // 取得目前選擇的類別
    const category = document.getElementById('category').value;
    let title, content;
    // 根據類別選擇適當的標題與內容欄位
    if (category === 'autobiography') {
        title = document.getElementById('title').value.trim();
        content = document.getElementById('content').value.trim();
    } else {
        title = document.getElementById('planTitle').value.trim();
        content = document.getElementById('planContent').value.trim();
    }
    // 檢查標題與內容是否填寫完整
    if (!title || !content) {
        alert('請輸入名稱及內容');
        return false;
    }
    // 確認是否要提交表單
    return confirm(`您確定要提交${category === 'autobiography' ? '自傳' : '讀書計畫'}：「${title}」？`);
}
</script>
        <!-- ========================= service-section end ========================= -->
        <!-- ========================= client-logo-section start ========================= -->
        <section class="client-logo-section pt-100">
            <div class="container">
                <div class="client-logo-wrapper">
                    <div class="client-logo-carousel d-flex align-items-center justify-content-between">
                        
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
