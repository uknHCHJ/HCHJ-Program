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

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>查看志願序</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">
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
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">查看個人資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="/~HCHJ/changepassword-01.html">修改密碼</a>
                                        </li>
                                        <li class="nav-item active"><a href="/~HCHJ/Home/contact-01(個人資料).php">個人資料</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">備審資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="/~HCHJ/Home/upload-01(上傳備審).php">上傳備審</a>
                                        </li>
                                        <li class="nav-item active"><a
                                                href="/~HCHJ/Home/recordforreview-01(備審紀錄).php">備審紀錄</a></li>
                                        <li class="nav-item"><a href="/~HCHJ/Home/messageboard-01(留言板).php ">導師留言板</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/blog-01(比賽資訊).php">比賽資訊</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/Contest-history(學生).php">競賽紀錄</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">志願序</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="/~HCHJ/Home/optional_write1.php">選填志願</a>
                                        </li>
                                        <li class="nav-item active"><a href="/~HCHJ/Home/optional_show1.php">查看志願序</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
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
                        <h2 class="text-white">查看志願序</h2>
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

    <!-- ========================= page-404-section end ========================= -->


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var urlParams = new URLSearchParams(window.location.search);
            var userValue = urlParams.get('user');

            // 顯示載入動畫
            document.getElementById('loading').style.display = 'flex';

            // 發送請求到後端取出資料
            fetch('optional_show2.php?user=' + encodeURIComponent(userValue))
                .then(response => response.json())
                .then(data => {
                    window.studentData = data;
                    window.isEditing = false; // 初始為非編輯狀態
                    window.isDataChanged = false; // 用於監測是否變更

                    renderTable(window.studentData);

                    // 隱藏載入動畫
                    document.getElementById('loading').style.display = 'none';
                })
                .catch(error => {
                    console.error('錯誤:', error);
                    // 隱藏載入動畫
                    document.getElementById('loading').style.display = 'none';
                });

            // 防止未保存變更就離開
            window.addEventListener('beforeunload', function (event) {
                if (window.isDataChanged) {
                    event.preventDefault();
                    event.returnValue = '您有未保存的變更，確定要離開嗎？';
                }
            });
        });

        // 渲染表格
        function renderTable(data) {
            var table = document.getElementById('data-table');
            var tbody = table.getElementsByTagName('tbody')[0];
            var saveChangesButton = document.getElementById('saveChangesButton'); // 假設你有一個保存變更的按鈕

            tbody.innerHTML = ''; // 清空表格內容

            if (data.length === 0) {
                // 顯示「您還未選擇任何志願」
                var row = tbody.insertRow();
                var cell = row.insertCell(0);
                cell.colSpan = 5;
                cell.textContent = '您還未選擇任何志願!';
                cell.style.textAlign = 'center';
                cell.style.color = 'gray';

                // 隱藏表格和編輯按鈕
                table.style.display = 'none';
                if (saveChangesButton) {
                    saveChangesButton.style.display = 'none'; // 隱藏「保存變更」按鈕
                }
                return;
            } else {
                // 顯示表格和編輯按鈕
                table.style.display = 'table';
                if (saveChangesButton) {
                    saveChangesButton.style.display = 'inline-block'; // 顯示「保存變更」按鈕
                }
            }

            data.forEach(function (item, index) {
                var row = tbody.insertRow();

                // 志願序號鎖定
                var preferenceCell = row.insertCell(0);
                preferenceCell.textContent = index + 1;  // 顯示順序號
                item.preference_rank = index + 1;  // 更新 preference_rank

                // 顯示學校名稱和科系
                row.insertCell(1).textContent = item.school_name;
                row.insertCell(2).textContent = item.department_name;

                // 顯示上傳時間
                var uploadTimeCell = row.insertCell(3);
                uploadTimeCell.textContent = item.time;

                // 最後一欄：變更順序，僅在編輯模式顯示
                var editCell = row.insertCell(4);
                if (window.isEditing) {
                    var container = document.createElement('div');
                    container.className = 'action-buttons';

                    var upArrow = document.createElement('button');
                    upArrow.innerHTML = '<span class="icon">↑</span>';
                    upArrow.className = 'btn btn-sm btn-primary';
                    upArrow.onclick = function () {
                        moveItem(index, -1);
                    };
                    container.appendChild(upArrow);

                    var downArrow = document.createElement('button');
                    downArrow.innerHTML = '<span class="icon">↓</span>';
                    downArrow.className = 'btn btn-sm btn-primary';
                    downArrow.onclick = function () {
                        moveItem(index, 1);
                    };
                    container.appendChild(downArrow);

                    editCell.appendChild(container);
                } else {
                    editCell.style.display = 'none'; // 隱藏單元格
                }
            });
        }



        // 移動項目位置
        function moveItem(index, direction) {
            var newIndex = index + direction;

            if (newIndex >= 0 && newIndex < window.studentData.length) {
                var temp = window.studentData[index];
                window.studentData[index] = window.studentData[newIndex];
                window.studentData[newIndex] = temp;

                // 更新 preference_rank
                window.studentData[index].preference_rank = index + 1;
                window.studentData[newIndex].preference_rank = newIndex + 1;

                window.isDataChanged = true; // 記錄變更
                renderTable(window.studentData);
            }
        }
    </script>



    <!-- 按鈕區 -->
    <div style="text-align: center; margin-top: 20px;">
        <button type="button" class="btn btn-secondary" style="background-color:#4CAF50; color: white;"
            onclick="window.location.href='optional_write1.php';">
            返回上一頁
        </button>
        <button id="editButton" type="button" class="btn btn-secondary" style="background-color:#4CAF50; color: white;"
            onclick="enableEditMode(this);">
            編輯
        </button>
        <button id="saveChangesButton" type="button" class="btn btn-secondary"
            style="background-color:#4CAF50; color: white; display: none;" onclick="saveChanges();">
            保存變更
        </button>
    </div>


    <!-- ========================= page-404-section end ========================= -->

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
                                            class="lni lni-facebook-filled"></i></a></li>
                                <li><a href="https://www.instagram.com/ukn_taipei/"><i
                                            class="lni lni-instagram-filled"></i></a></li>
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