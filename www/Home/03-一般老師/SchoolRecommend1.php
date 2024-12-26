<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.html");
    exit();
}

$userData = $_SESSION['user']; // 從 SESSION 獲取用戶資料
$userId = htmlspecialchars($userData['user'], ENT_QUOTES, 'UTF-8'); // 確保數據安全

$query = sprintf("SELECT * FROM user WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);

if (!isset($_SESSION['user'])) {
    echo("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}

// 獲取用戶的統測成績
$userId = $_SESSION['user']['user'];
$query = "SELECT * FROM user_scores WHERE user = ?";
$stmt = $link->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// 儲存用戶的成績
$userScores = [];
while ($row = $result->fetch_assoc()) {
    $userScores[$row['subject_name']] = $row['score'];
}
$stmt->close();

// 從資料庫提取學校資料
$query = "SELECT * FROM weighted";
$result = $link->query($query);

// 儲存學校資料
$schools = [];
while ($row = $result->fetch_assoc()) {
    $schools[] = [
        'school_name' => $row['school_name'],
        'location' => $row['location'],
        'required_scores' => json_decode($row['required_scores'], true), // 解碼 JSON
        'link' => $row['link']
    ];
}

// 計算每個學校的進入機率
$recommendations = [];
foreach ($schools as $school) {
    $totalCriteria = count($school['required_scores']);
    $matchedCriteria = 0;

    // 計算符合的條件數量
    foreach ($school['required_scores'] as $subject => $requiredScore) {
        if (isset($userScores[$subject]) && $userScores[$subject] >= $requiredScore) {
            $matchedCriteria++;
        }
    }

    // 計算進入機率
    $probability = ($matchedCriteria / $totalCriteria) * 100;
    $recommendations[] = [
        'school_name' => $school['school_name'],
        'probability' => $probability,
        'link' => $school['link']
    ];
}

// 排序推薦學校，按機率排序
usort($recommendations, function ($a, $b) {
    return $b['probability'] - $a['probability'];
});
?>

<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>推薦二技</title>
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
    /* 設定容器和表單樣式 */
    .form-container {
        width: 100%;
        max-width: 800px; /* 設定最大寬度 */
        margin: 0 auto; /* 容器居中 */
        padding: 20px;
        text-align: center; /* 使所有內容置中 */
    }
    .large-title {
        font-size: 2.5em; /* 調整為所需的大小 */
    }
    /* 設定表格樣式 */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        text-align: center; /* 表格內容居中 */
    }

    table th, table td {
        padding: 10px;
        border: 1px solid #ddd;
    }

    /* 設定圖表樣式 */
    canvas {
        display: block;
        margin: 20px auto; /* 讓圖表居中 */
        max-width: 800px; /* 設定最大寬度 */
    }

    /* 標題樣式 */
    h1, h2 {
        font-size: 1.8em;
        color: #333;
        text-align: center; /* 標題居中 */
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
                    <h1 class="text-white text-left large-title">學校推薦</h1><br> <!-- 讓標題靠左並增加字體大小 -->
                    <div class="page-breadcrumb text-left"> <!-- 讓麵包屑導航靠左 -->
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
        <div class="form-container">
    <h1>學校推薦</h1>
    <canvas id="probabilityChart" width="800" height="400"></canvas>

    <table>
        <tr>
            <th>學校名稱</th>
            <th>推薦機率 (%)</th>
            <th>詳細資訊</th>
        </tr>
        <?php if (count($recommendations) > 0) : ?>
            <?php foreach ($recommendations as $school) : ?>
                <tr>
                    <td><?php echo $school['school_name']; ?></td>
                    <td><?php echo round($school['probability'], 2); ?>%</td>
                    <td><a href="<?php echo $school['link']; ?>" target="_blank">查看學校</a></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">沒有找到推薦的學校。</td>
            </tr>
        <?php endif; ?>
    </table>

    <h2>推薦機率圖表</h2>
    <canvas id="probabilityChart" width="400" height="200"></canvas>
</div>

<script>
        const ctx = document.getElementById('probabilityChart').getContext('2d');

        // 初始化圖表
        const probabilityChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [], // 學校名稱
                datasets: [{
                    label: '推薦機率 (%)',
                    data: [], // 學校推薦機率
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10, // 每 10% 顯示一個刻度
                            max: 100 // 最大值為 100%
                        }
                    }
                }
            }
        });

        // 定期更新圖表數據
        async function fetchDataAndUpdateChart() {
            try {
                const response = await fetch('get_data.php'); // 假設從API或資料庫獲取數據
                const data = await response.json(); // 假設返回的數據格式為 { "學校1": 80, "學校2": 65, ... }

                // 提取學校名稱和機率數據
                const labels = Object.keys(data);
                const probabilities = Object.values(data);

                // 更新圖表數據
                probabilityChart.data.labels = labels;
                probabilityChart.data.datasets[0].data = probabilities;
                probabilityChart.update(); // 刷新圖表
            } catch (error) {
                console.error('數據加載失敗:', error);
            }
        }

        // 每 5 秒更新一次圖表數據
        setInterval(fetchDataAndUpdateChart, 5000);

        // 初次加載數據
        fetchDataAndUpdateChart();
    </script>
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
