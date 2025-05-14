<!doctype html>
<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');
} else {
    echo "資料庫連接失敗: " . mysqli_connect_error();
    exit();
}

if (!isset($_SESSION['user'])) {
    echo("<script>
            alert('請先登入！！');
            window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user'];
// 從 SESSION 中取得使用者資訊
$username = $userData['name'];
$userId   = $userData['user'];
$grade    = $_GET['grade'];
$class    = $_GET['class'];

// 檢查使用者是否為導師
$query_role = "SELECT Permissions FROM user WHERE user = '$userId'";
$result_role = mysqli_query($link, $query_role);

if ($result_role) {
    $row_role = mysqli_fetch_assoc($result_role);
    $user_role = $row_role['Permissions'];

    // 轉換字串為陣列
    $permissionsArray = explode(',', $user_role);

    // 檢查是否包含 '2'
    if (!in_array('2', $permissionsArray)) {
        echo "<script>
                alert('您沒有權限查看此頁面');
                window.location.href = '../index.html';
              </script>";
        exit();
    }
} else {
    echo "權限查詢失敗：" . mysqli_error($link);
    exit();
}
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>學生檔案上傳狀態</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
        <!-- CSS -->
        <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/tiny-slider.css">
        <link rel="stylesheet" href="assets/css/glightbox.min.css">
        <link rel="stylesheet" href="assets/css/main.css">
    </head>
    <body>
        <!-- preloader start -->
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

        <!-- header start -->
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
                                    <a class="nav-item dd-menu">學生管理</a>
                                    <ul class="sub-menu">
                                    <li class="nav-item"><a href="student02-1.php">學生備審管理</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序總覽(技優)</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics3-01.php">志願序總覽(申請)</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02(2).php">繳交志願序(技優)</a></li>
                                        <li class="nav-item"><a href="settime02-1.php">志願序開放時間</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="Schoolnetwork1-02.php">二技校園網</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">比賽資訊</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog-02.php">頁首</a></li>
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
                                    <a class="page-scroll" href="../logout.php">登出</a>
                                </li>
                        </div> <!-- navbar collapse -->
                    </nav> <!-- navbar -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->

    </header>
        <!-- header end -->
        <!-- page-banner-section start -->
        <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="banner-content">
                            <h2 class="text-white">學生檔案上傳狀態</h2>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- page-banner-section end -->

        <section class="container mt-5">
            <div class="table-header">
                <h2>檔案上傳狀態（備審資料最終版）</h2>
                <form id="searchForm" class="search-form">
                <input type="text" name="query" id="query" placeholder="輸入學號搜尋..." oninput="searchStudents()">
                    <button type="submit">
                    <i class="lni lni-search-alt"></i>
                    </button>
                </form>
        <script>
            function searchStudents() {
                var input = document.getElementById("query").value.trim().toLowerCase();
                var rows = document.querySelectorAll("tbody tr");

                rows.forEach(function(row) {
                    var studentId = row.cells[0].innerText.toLowerCase(); // 取得學號
                    if (studentId.includes(input)) {
                        row.style.display = ""; // 顯示符合的行
                    } else {
                        row.style.display = "none"; // 隱藏不符合的行
                    }
                });
            }
        </script>

            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>學號</th>
                        <th>姓名</th>
                        <th>最後上傳時間</th>
                        <th class='text-center'>是否上傳</th>
                        <th class='text-center'>上傳筆數</th>    
                        <th class='text-center'>檔案下載</th>              
                    </tr>
                </thead>
                <tbody>
                <?php
$query_students = "SELECT user, name FROM user WHERE class='$class' AND grade='$grade' AND Permissions='1,9' ORDER BY user ASC";
$result_students = mysqli_query($link, $query_students);

if ($result_students) {
    while ($student = mysqli_fetch_assoc($result_students)) {
        $student_id = $student['user'];
        $student_name = $student['name'];

        $query = "SELECT MAX(upload_time) AS latest_upload, COUNT(*) AS upload_count 
                  FROM portfolio 
                  WHERE student_id='$student_id' AND category='備審(最終版)'";
        $result = mysqli_query($link, $query);
        $row = mysqli_fetch_assoc($result);
        
        $upload_count = $row['upload_count'] ?? 0;
        $latest_upload = $row['latest_upload'] ?? '無紀錄';
        $status = ($upload_count > 0) ? "✔️" : "❌";
        $download_link = ($upload_count > 0) ? "<a href='teacher-download-04.php?id={$student_id}&category=備審(最終版)'>📂 下載</a>" : "";


        echo "<tr>
                <td>{$student_id}</td>
                <td>{$student_name}</td>
                <td>{$latest_upload}</td>
                <td class='text-center'>{$status}</td>
                <td class='text-center'>{$upload_count}</td>
                <td class='text-center'>{$download_link}</td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='5'>查詢失敗：" . mysqli_error($link) . "</td></tr>";
}
?>
</tbody>
            </table>
            <div class="text-center mt-3">
                <button class="btn btn-primary" onclick="history.back()">返回上一頁</button>
            </div>
        </section>

        <!-- client-logo-section start -->
        <section class="client-logo-section pt-100">
            <div class="container">
                <div class="client-logo-wrapper">
                    <div class="client-logo-carousel d-flex align-items-center justify-content-between">
                        <div class="client-logo">

                        </div>
                        <div class="client-logo">

                        </div> 
                        <div class="client-logo">

                        </div>
                        <div class="client-logo">

                        </div>
                        <div class="client-logo">

                        </div>
                        <div class="client-logo">

                        </div>
                        <div class="client-logo">

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- client-logo-section end -->
        <!-- footer start -->
        <footer class="footer pt-100">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                            <a href="index-04.php" class="logo mb-30">
                                <img src="schoolimages/uknlogo.png" alt="logo">
                            </a>
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
                                    align-items: center;
                                    justify-content: space-between;
                                }
                                .footer-widget {                                   
                                    text-align: right;
                                }
                                .table-header {
                                    display: flex;
                                    justify-content: space-between; /* 讓標題靠左，搜尋框靠右 */
                                    align-items: center;
                                    margin-bottom: 10px; /* 調整與表格的間距 */
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
        <!-- footer end -->

        <!-- scroll-top -->
        <a href="#" class="scroll-top">
            <i class="lni lni-arrow-up"></i>
        </a>
        
        <!-- JS -->
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
