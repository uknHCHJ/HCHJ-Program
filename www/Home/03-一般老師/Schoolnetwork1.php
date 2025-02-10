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
                            <h2 class="text-white">二技科系</h2>
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
        <?php 
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連接資料庫失敗: " . $conn->connect_error);
}

// 讀取 Secondskill 表資料
$sql = "SELECT id, name, public_private, address, website FROM Secondskill";
$result = $conn->query($sql);

// 存放學校資料
$schools = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
}

// 判斷學校區域的函式
function getRegion($address) {
    $north = ['臺北市', '新北市', '基隆市', '桃園市', '新竹市', '新竹縣', '宜蘭縣'];
    $central = ['臺中市', '苗栗縣', '彰化縣', '南投縣', '雲林縣'];
    $south = ['高雄市', '臺南市', '屏東縣', '嘉義市', '嘉義縣', '澎湖縣'];
    $east = ['花蓮縣', '臺東縣', '金門縣', '連江縣'];

    foreach ($north as $region) {
        if (strpos($address, $region) !== false) return 'north';
    }
    foreach ($central as $region) {
        if (strpos($address, $region) !== false) return 'central';
    }
    foreach ($south as $region) {
        if (strpos($address, $region) !== false) return 'south';
    }
    foreach ($east as $region) {
        if (strpos($address, $region) !== false) return 'east';
    }
    return 'unknown';
}
?>

<!-- ========================= service-section start ========================= -->
<body>
<section class="container mt-5 d-flex justify-content-center align-items-center flex-column">
    <h2 class="text-center" style="font-size: 3em; line-height: 1.2; color: #333;"><?= $school_name ?></h2><br>
    
    <script>
    function filterSchools(region) {
        let items = document.querySelectorAll('.grid-item');
        items.forEach(item => {
            if (region === '*' || item.classList.contains(region)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });

        // 移除所有按鈕的 'active' 類別
        document.querySelectorAll('.portfolio-btn').forEach(btn => {
            btn.classList.remove('active');
        });

        // 給當前按鈕添加 'active' 類別
        document.querySelector(`[data-filter='${region}']`).classList.add('active');
    }

    function toggleActiveButton(button) {
        // 移除所有按鈕的 'active' 類別
        document.querySelectorAll('.theme-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        // 給當前按鈕添加 'active' 類別
        button.classList.add('active');
    }
    </script>

    <h1 class="text-center" style="font-size: 2.5em; color: #2C3E50; font-weight: bold;">二技校園網</h1>
    <div class="text-center mb-4">
        <button type="button" class="portfolio-btn active" onclick="filterSchools('*')" data-filter="*">全部</button>
        <button type="button" class="portfolio-btn" onclick="filterSchools('north')" data-filter="north">北部</button>
        <button type="button" class="portfolio-btn" onclick="filterSchools('central')" data-filter="central">中部</button>
        <button type="button" class="portfolio-btn" onclick="filterSchools('south')" data-filter="south">南部</button>
        <button type="button" class="portfolio-btn" onclick="filterSchools('east')" data-filter="east">東部</button>
    </div>

    <!-- 使用 flexbox 來顯示資料項目 -->
    <div class="grid-container" style="display: flex; flex-wrap: wrap; justify-content: space-around;">
    <?php
    // 假設 $schools 是你從資料庫中取得的學校資料
    foreach ($schools as $school) {
        // 依照地址判斷區域
        $location = getRegion($school["address"]);
        echo "<div class='grid-item $location' style='width: 30%; margin: 15px; border: 1px solid #ddd; padding: 15px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); background-color: white; color: black;'>";
        echo "    <h3 style='font-size: 1.8em; color: #16A085;'>" . htmlspecialchars($school["name"]) . "</h3>";
        echo "    <p><strong>公/私立:</strong> " . htmlspecialchars($school["public_private"]) . "</p>";
        echo "    <p><strong>地址:</strong> " . htmlspecialchars($school["address"]) . "</p>";
        echo "    <a href='" . htmlspecialchars($school["website"]) . "' class='theme-btn' target='_blank'>查看詳細資料</a>";
        echo "    <a href='Schoolnetwork2.php?school_id=" . htmlspecialchars($school['id']) . "' class='theme-btn' target='_blank'>二技科系</a>";
        echo "</div>";
    }
    ?>
    </div>
</section>
</body>

<style>
    /* 北中南東部分類按鈕框線淡顏色 */
    .portfolio-btn {
        background-color: white;
        color: black;
        padding: 10px 20px;
        border: 1px solid #BDC3C7; /* 淡灰色框線 */
        border-radius: 5px;
        font-size: 1em;
        margin: 5px;
        transition: background-color 0.3s, color 0.3s;
    }

    /* 當按鈕被選中時顯示藍色背景 */
    .portfolio-btn.active {
        background-color: #3498DB;
        color: white;
    }

    /* 按鈕懸停變色 */
    .portfolio-btn:hover {
        background-color: #3498DB;
        color: white;
    }

   /* 查看詳細資料和二技科系按鈕樣式 */
.theme-btn {
    background-color: white; /* 底色為白色 */
    color: black; /* 文字顏色為黑色 */
    padding: 10px 20px;
    text-decoration: none;
    border: 1px solid #BDC3C7; /* 淡灰色邊框 */
    border-radius: 5px;
    transition: background-color 0.3s, color 0.3s;
}

/* 按鈕按下時，底色變為藍色 */
.theme-btn:active {
    background-color: #3498DB; /* 藍色 */
    color: white; /* 文字顏色變為白色 */
}

/* 按鈕懸停時變色 */
.theme-btn:hover {
    background-color: #3498DB; /* 藍色 */
    color: white; /* 文字顏色變為白色 */
}

/* 學校資料項目 */
.grid-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.grid-item {
    width: 30%;
    margin: 15px;
    border: 1px solid #ddd;
    padding: 15px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: white;
    color: black;
}

.grid-item.north { background-color: white; }
.grid-item.central { background-color: white; }
.grid-item.south { background-color: white; }
.grid-item.east { background-color: white; }


</style>

<!-- ========================= service-section end ========================= -->
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
