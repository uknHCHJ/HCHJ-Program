<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 1. 從 Preferences 表中取得資料並聯接 user 表獲取姓名
$sql_preferences = "SELECT p.school_id, p.user, u.name FROM Preferences p JOIN user u ON p.user = u.user";
$result_preferences = $conn->query($sql_preferences);

// 儲存 Preferences 資料
$preferences = [];
if ($result_preferences->num_rows > 0) {
    while ($row = $result_preferences->fetch_assoc()) {
        $preferences[] = $row; // 儲存每一筆資料，包含學號與姓名
    }
}

// 2. 從 user 表中取得資料，篩選 grade = 5 並且 class = 'B'
$sql_users = "SELECT user FROM user WHERE grade = 5 AND class = 'B'";
$result_users = $conn->query($sql_users);

// 儲存符合條件的使用者
$valid_users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $valid_users[] = $row['user']; // 只儲存使用者的 user 欄位
    }
}

// 3. 將兩表進行匹配並統計每所學校被選擇的次數
$school_counts = [];
foreach ($preferences as $preference) {
    if (in_array($preference['user'], $valid_users)) {
        // 如果此使用者在符合條件的使用者中
        $school_id = $preference['school_id'];
        $user_name = $preference['name']; // 儲存使用者的姓名
        if (!isset($school_counts[$school_id])) {
            $school_counts[$school_id] = [
                'count' => 0, // 初始化學校計數
                'users' => [] // 儲存使用者姓名
            ];
        }
        $school_counts[$school_id]['count']++;
        $school_counts[$school_id]['users'][] = $user_name; // 將使用者姓名加入
    }
}

// 4. 取得學校名稱
$sql_schools = "SELECT school_id, school_name FROM School";
$result_schools = $conn->query($sql_schools);

// 學校名稱對照表
$school_names = [];
if ($result_schools->num_rows > 0) {
    while ($row = $result_schools->fetch_assoc()) {
        $school_names[$row['school_id']] = $row['school_name']; // 以 school_id 為鍵，school_name 為值
    }
}

// 5. 組合資料並準備長條圖
$chart_data = [];
foreach ($school_counts as $school_id => $count_data) {
    $school_name = isset($school_names[$school_id]) ? $school_names[$school_id] : "未知學校";
    $chart_data[] = [
        "school_id" => $school_id, // 傳遞 school_id
        "school_name" => $school_name,
        "count" => $count_data['count'],
        "users" => $count_data['users'] // 傳遞使用者姓名
    ];
}

// 關閉資料庫連線
$conn->close();
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>學生志願序統計</title>
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
                        <a class="navbar-brand" href="index-02.php">
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
                                <li class="nav-item"><a href="index-02.php">首頁</a></li>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">個人資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="contact02-1.php">查看個人資料</a></li>
                                        <li class="nav-item"><a href="/~HCHJ/changepassword.html">修改密碼</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="student02-1.php">學生管理</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">二技校園網</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Schoolnetwork1-02.php">首頁</a></li>
                                        <li class="nav-item"><a href="AddSchool1-02.php">新增校園</a></li>
                                        <li class="nav-item"><a href="SchoolEdit1-02.php">編輯詳細資料</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">比賽資訊</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog-02.php">查看</a></li>
                                        <li class="nav-item"><a href="AddContest1-02.php">新增</a></li>
                                        <li class="nav-item"><a href="ContestEdin1-02.php">編輯</a></li>
                                    </ul>
                                </li>


                                <li class="nav-item">
                                    <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Permission.php">切換使用者</a>
                                </li>
                                <li class="nav-item">
                                    <a href="javascript:void(0)" onclick="submitLogout()">登出</a>
                                </li>
                        </div> <!-- navbar collapse -->
                    </nav> <!-- navbar -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->

    </header>
    <!-- ========================= header end ========================= -->

    <!-- ========================= page-banner-section start ========================= -->
    <section class="page-banner-section pt-75 pb-75 img-bg"
    style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="banner-content">
            <h2 class="text-white">學生管理</h2>
            <div class="page-breadcrumb">
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item" aria-current="page"><a href="index-02.php">首頁</a></li>
                  <li class="breadcrumb-item active" aria-current="page">查看學生備審</li>
                </ol>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>志願統計</title>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                text-align: center;
            }

            .chart-container {
                display: flex;
                justify-content: center;
                margin: 20px auto;
                max-width: 1000px;
            }

            canvas {
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
        </style>
    </head>

    <body>
        <h1>班級志願統計</h1>
        <div class="chart-container">
            <canvas id="barChart" width="1000" height="500"></canvas> <!-- 設定高度為 500 -->
        </div>

        <h2>科系志願統計</h2>
        <div class="chart-container">
            <canvas id="departmentChart" width="1000" height="500"></canvas><!-- 設定高度為 500 -->
        </div>
    </body>


    <script>
        // 從 PHP 傳遞資料到 JavaScript
        const chartData = <?php echo json_encode($chart_data); ?>;

        // 提取學校名稱和人數
        const labels = chartData.map(data => data.school_name);
        const data = chartData.map(data => data.count);

        // 初始化學校長條圖
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '選擇人數',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                aspectRatio: 1.5,//修改長寬比例
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            afterLabel: function (context) {
                                const index = context.dataIndex;
                                const studentNames = studentNamesList[index];
                                if (studentNames && Array.isArray(studentNames)) {
                                    return '學生姓名:\n' + studentNames.join('\n');
                                } else {
                                    return '學生姓名: 無資料';
                                }
                            }
                        }
                    }
                }
            }
        });

        // 初始化科系圖表
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        let departmentChart;

        document.getElementById('barChart').onclick = function (evt) {
            const points = barChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);

            if (points.length) {
                const index = points[0].index;
                const selectedSchool = chartData[index];
                const schoolId = selectedSchool.school_id;

                // AJAX 獲取科系數據
                fetch(`get_departments2-02.php?school_id=${schoolId}`)
                    .then(response => response.json())
                    .then(departmentData => {
                        const labels = departmentData.map(data => data.department_name);
                        const data = departmentData.map(data => data.count);
                        const studentNamesList = departmentData.map(data => data.student_names);

                        if (departmentChart) {
                            departmentChart.destroy();
                        }
                        departmentChart = new Chart(departmentCtx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: '選擇人數',
                                    data: data,
                                    backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                aspectRatio: 2,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            afterLabel: function (context) {
                                                const index = context.dataIndex;
                                                const studentNames = studentNamesList[index];
                                                return '學生姓名: ' + studentNames;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
            }
        };


    </script>
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
                                align-items: center;
                                /* 垂直居中 */
                                justify-content: space-between;
                                /* 讓兩個區塊分居左右 */
                            }

                            .footer-widget {
                                text-align: right;
                                /* 讓「關於學校」內容靠右對齊 */
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
                                <li><a href="https://www.facebook.com/UKNunversity"><i
                                            class="lni lni-facebook-filled"></i></a>
                                </li>
                                <li><a href="https://www.instagram.com/ukn_taipei/"><i
                                            class="lni lni-instagram-filled"></i></a>
                                </li>
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

</body>

</html>