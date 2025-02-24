<?php
session_start();
/** 資料庫連線 */
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
    mysqli_query($link, 'SET NAMES UTF8');

} else {
    echo "資料庫連接失敗: " . mysqli_connect_error();
}

if (!isset($_SESSION['user'])) {
    echo ("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}

$userData = $_SESSION['user'];
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$username = $userData['name']; // 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>志願序開放時間編輯</title>
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
                                    <a class="nav-item dd-menu">學生管理</a>
                                    <ul class="sub-menu">
                                    <li class="nav-item"><a href="student02-1.php">學生備審管理</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序總覽</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02(2).php">繳交志願序</a></li>
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
    <!-- ========================= header end ========================= -->

    <!-- ========================= page-banner-section start ========================= -->
    <section class="page-banner-section pt-75 pb-75 img-bg"
        style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="banner-content">
                        <h2 class="text-white" style="text-align: left; margin-left: 20px;">志願序開放時間編輯</h2>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!DOCTYPE html>
    <html lang="zh">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>志願序開放時間編輯</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <style>
            /* 設置時間區塊分開顯示 */
            .time-block {
                margin-bottom: 20px;
            }

            /* 增加區塊樣式設計 */
            #current-time,
            #no-time-set {
                padding: 15px;
                background-color: #f1f1f1;
                border-radius: 8px;
                margin-bottom: 20px;
                font-size: 16px;
            }

            #current-time h2,
            #no-time-set {
                font-size: 18px;
                color: #333;
            }

            #no-time-set {
                color: #d9534f;
                font-weight: bold;
            }

            #timeForm {
                margin-top: 20px;
            }

            .time-settings-container {
                max-width: 500px;
                margin: 50px auto;
                padding: 30px;
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                font-family: 'Arial', sans-serif;
            }

            .time-settings-container h1 {
                font-size: 26px;
                color: #333;
                text-align: center;
                margin-bottom: 20px;
            }

            .time-settings-container label {
                font-weight: bold;
                color: #555;
                margin-top: 15px;
                display: block;
                font-size: 16px;
            }

            .time-settings-container input[type="datetime-local"] {
                width: 100%;
                padding: 12px;
                margin-top: 8px;
                border: 2px solid #ddd;
                border-radius: 8px;
                font-size: 16px;
                background-color: #f9f9f9;
                transition: all 0.3s ease;
            }

            .time-settings-container input[type="datetime-local"]:focus {
                border-color: #007bff;
                background-color: #e6f0ff;
                outline: none;
            }

            .time-settings-container button {
                width: 100%;
                background-color: #28a745;
                color: white;
                border: none;
                padding: 14px;
                margin-top: 20px;
                border-radius: 8px;
                cursor: pointer;
                font-size: 18px;
                transition: background 0.3s ease;
            }

            .time-settings-container button:hover {
                background-color: #218838;
            }

            .time-settings-container .error {
                color: red;
                margin-top: 10px;
                font-size: 14px;
                text-align: center;
            }

            /* 響應式設計，適應不同螢幕尺寸 */
            @media (max-width: 768px) {
                .time-settings-container {
                    width: 80%;
                    padding: 20px;
                }

                .time-settings-container h1 {
                    font-size: 22px;
                }

                .time-settings-container input[type="datetime-local"],
                .time-settings-container button {
                    font-size: 14px;
                    padding: 10px;
                }

            }
        </style>
    </head>

    <body>
        <div class="time-settings-container">
            <h1>設定選填志願時間</h1>

            <!-- 顯示當前設定時間 -->
            <div id="current-time">
                <h2>當前設定時間</h2>
                <p id="current-start-time"></p>
                <p id="current-end-time"></p>
                <p id="no-time-set" style="color: red; font-weight: bold;"></p>
            </div>

            <form id="timeForm">
                <label for="startTime">開始時間：</label>
                <input type="datetime-local" id="startTime" name="startTime"><br><br>

                <label for="endTime">結束時間：</label>
                <input type="datetime-local" id="endTime" name="endTime"><br><br>

                <button type="submit">保存</button>
                <div id="error" class="error"></div>
            </form>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                fetch('get_time2-02.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.open_time && data.close_time) {
                            const currentTime = new Date();
                            const startTime = new Date(data.open_time);
                            const endTime = new Date(data.close_time);

                            // 設定開始時間選擇器的 min 為當前時間
                            document.getElementById('startTime').min = new Date().toISOString().slice(0, 16);

                            // 若設定的時間已經結束
                            if (currentTime > endTime) {
                                document.getElementById('no-time-set').textContent = '您還未設定時間';
                                document.getElementById('startTime').value = '';
                                document.getElementById('endTime').value = '';
                            } else {
                                // 顯示當前的設定時間
                                document.getElementById('current-start-time').textContent = `開始時間: ${data.open_time}`;
                                document.getElementById('current-end-time').textContent = `結束時間: ${data.close_time}`;
                                document.getElementById('no-time-set').textContent = '';
                            }
                        } else {
                            // 若沒有設定過時間
                            document.getElementById('no-time-set').textContent = '您還未設定時間';
                            document.getElementById('startTime').value = '';
                            document.getElementById('endTime').value = '';
                        }
                    })
                    .catch(error => console.error('Error fetching time:', error));

                // 當使用者選擇開始時間時，動態設定結束時間的 min
                document.getElementById('startTime').addEventListener('input', function () {
                    document.getElementById('endTime').min = this.value;
                });
            });

            document.getElementById('timeForm').addEventListener('submit', function (event) {
                event.preventDefault();

                const startTime = document.getElementById('startTime').value;
                const endTime = document.getElementById('endTime').value;

                const errorElement = document.getElementById('error');
                errorElement.textContent = ''; // 清空之前的錯誤訊息

                if (!startTime || !endTime) {
                    errorElement.textContent = '請輸入完整的開始與結束時間';
                    return;
                }

                if (new Date(endTime) < new Date(startTime)) {
                    errorElement.textContent = '結束時間不能早於開始時間';
                    return;
                }

                const data = JSON.stringify({ startTime, endTime });

                fetch('settime02-2.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: data
                })
                    .then(response => response.json())
                    .then(result => {
                        alert(result.success ? '設定成功！' : '設定失敗：' + result.message);
                        if (result.success) {
                            // 設定成功後刷新顯示當前時間
                            document.getElementById('current-start-time').textContent = `開始時間: ${startTime}`;
                            document.getElementById('current-end-time').textContent = `結束時間: ${endTime}`;
                            document.getElementById('no-time-set').textContent = '';
                        }
                    })
                    .catch(error => alert('發生錯誤，請稍後再試！'));
            });
        </script>

    </body>

    </html>
    <!-- ========================= client-logo-section end ========================= -->


    <!-- ========================= footer start ========================= -->
    <footer class="footer pt-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                        <a href="index-04.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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