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
    <title>二技志願填選</title>
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
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">個人資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="../changepassword-01(修改密碼).html">修改密碼</a>
                                        </li>
                                        <li class="nav-item"><a href="/~HCHJ/Home/contact-01(個人資料).html">查看個人資料</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll active dd-menu" href="javascript:void(0)">備審資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="\Personal page\html\index.html">導師留言板</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="\Personal page\html\index.html">比賽資訊</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="\Personal page\html\index.html">競賽紀錄</a>
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
                                <li class="nav-item">
                                <li class="nav-item active"><a href="../changepassword-01.html">修改密碼</a></li>
                            </ul>
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
                        <h2 class="text-white">二技志願選填</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">

                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <head>
        <!-- ========================= CSS here ========================= -->
        <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/glightbox.min.css">
        <link rel="stylesheet" href="assets/css/tiny-slider.css">
        <link rel="stylesheet" href="assets/css/main.css">

        <style>
            body {
                font-family: Arial, sans-serif;
            }

            select,
            button {
                margin: 10px 0;
                padding: 5px;
            }
        </style>
    </head>

    <body>

        <!-- ========================= header start ========================= -->
        <header class="header navbar-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="index-01.php">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                        </nav> <!-- navbar -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </header>

        <head>
            <meta charset="utf-8">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <title>二技志願填選</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

            <!-- ========================= CSS here ========================= -->
            <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
            <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
            <link rel="stylesheet" href="assets/css/animate.css">
            <link rel="stylesheet" href="assets/css/tiny-slider.css">
            <link rel="stylesheet" href="assets/css/glightbox.min.css">
            <link rel="stylesheet" href="assets/css/main.css">

            <style>
                body {
                    font-family: Arial, sans-serif;
                }

                select,
                button {
                    margin: 10px 0;
                    padding: 5px;
                }

                select {
                    appearance: none;
                    padding: 10px;
                    width: 100%;
                    max-width: 100%;
                    font-size: 16px;
                    border: 2px solid #ccc;
                    border-radius: 8px;
                    background-color: #fff;
                    color: #333;
                    margin-bottom: 15px;
                    transition: border-color 0.3s ease;
                }

                select:focus {
                    border-color: #5cb85c;
                    outline: none;
                }

                select {
                    appearance: none;
                    padding: 8px;
                    width: 100%;
                    font-size: 1em;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    background-color: #fff;
                    color: #333;
                    margin-bottom: 10px;
                    transition: border-color 0.3s ease;
                }

                select:focus {
                    border-color: #5cb85c;
                    outline: none;
                }

                /* 改善送出按鈕樣式 */
                button {
                    background-color: #5cb85c;
                    color: white;
                    font-size: 1em;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                button:hover {
                    background-color: #4cae4c;
                }

                /* 調整結果顯示區塊的樣式 */
                #preferenceList {
                    background-color: #f9f9f9;
                    /* 背景色淡灰 */
                    padding: 15px;
                    /* 內距 */
                    border: 1px solid #ddd;
                    /* 邊框 */
                    border-radius: 8px;
                    /* 圓角 */
                    margin-top: 10px;
                    max-height: 200px;
                    /* 限制最大高度 */
                    overflow-y: auto;
                    /* 當內容超過時滾動 */
                }

                /* 每個志願項目樣式 */
                #preferenceList li {
                    background-color: #e7f3fe;
                    /* 輕微藍色底 */
                    margin: 5px 0;
                    padding: 10px;
                    border-left: 5px solid #2196F3;
                    /* 左側藍條 */
                    color: #333;
                }
            </style>
        </head>

        <body>
            <!-- ========================= header start ========================= -->
            <header class="header navbar-area">
                <!-- header code ... -->
            </header>
            <!-- ========================= header end ========================= -->

            <!-- ========================= form section start ========================= -->
            <section class="form-section pt-75 pb-75">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="container">
                                <h1>選擇你的志願</h1>

                                <label for="schoolSelect">選擇學校:</label>
                                <select id="schoolSelect" onchange="fetchDepartments()">
                                    <option value="">--請選擇學校--</option>
                                </select>

                                <label for="departmentSelect">選擇科系:</label>
                                <select id="departmentSelect">
                                    <option value="">--請選擇科系--</option>
                                </select>

                                <!-- 新增兩個按鈕 -->
                                <button onclick="add()">添加到清單</button>

                                <h2>你的志願序(最多5個)</h2>
                                <ul id="preferenceList"></ul>
                                <button onclick="submit()">送出志願</button>

                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    const maxPreferences = 5;
                    let preferences = [];

                    document.addEventListener('DOMContentLoaded', () => {
                        fetchSchools();
                    });

                    function fetchSchools() {
                        fetch('getSchools.php')
                            .then(response => response.json())
                            .then(data => {
                                const schoolSelect = document.getElementById('schoolSelect');
                                data.forEach(school => {
                                    const option = document.createElement('option');
                                    option.value = school.school_id;
                                    option.textContent = school.school_name;
                                    schoolSelect.appendChild(option);
                                });
                            });
                    }

                    function fetchDepartments() {
                        const schoolId = document.getElementById('schoolSelect').value;
                        if (!schoolId) return;

                        fetch(`getDepartments.php?school_id=${schoolId}`)
                            .then(response => response.json())
                            .then(data => {
                                const departmentSelect = document.getElementById('departmentSelect');
                                departmentSelect.innerHTML = '<option value="">--請選擇科系--</option>';
                                data.forEach(dept => {
                                    const option = document.createElement('option');
                                    option.value = dept.department_id;
                                    option.textContent = dept.department_name;
                                    departmentSelect.appendChild(option);
                                });
                            });
                    }

                    function add() {
                        const schoolSelect = document.getElementById('schoolSelect');
                        const departmentSelect = document.getElementById('departmentSelect');

                        // 確保選擇了學校和科系
                        if (!schoolSelect.value || !departmentSelect.value) {
                            alert('請先選擇學校和科系');
                            return;
                        }

                        const preference = `${schoolSelect.options[schoolSelect.selectedIndex].text} - ${departmentSelect.options[departmentSelect.selectedIndex].text}`;

                        // 檢查志願是否已經被選過
                        if (preferences.some(p => p.preference === preference)) {
                            alert('此志願已經選擇過，請選擇其他的志願');
                            return;
                        }

                        if (preferences.length >= maxPreferences) {
                            alert('最多只能選擇5個志願');
                            return;
                        }

                        // 添加序號和選擇的志願資訊
                        const order = preferences.length + 1;
                        preferences.push({
                            order: order,
                            schoolId: schoolSelect.value,
                            departmentId: departmentSelect.value,
                            preference: preference
                        });

                        // 顯示志願清單並添加序號
                        const preferenceList = document.getElementById('preferenceList');
                        const li = document.createElement('li');
                        li.textContent = `${order}. ${preference}`;
                        preferenceList.appendChild(li);
                    }

                    function submit() {
                        if (preferences.length === 0) {
                            alert("請先添加至少一個志願");
                            return;
                        }

                        fetch("addPreference.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                            },
                            body: JSON.stringify({
                                preferences: preferences.map((pref, index) => ({
                                    serial_number: index + 1,
                                    school_id: pref.school_id,
                                    department_id: pref.departmentId
                                })),
                            }),
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert("志願序送出成功");
                                    window.location.href = 'optional_show1.php';
                                } else {
                                    alert("儲存失敗: " + data.message);
                                }
                            })
                            .catch(error => {
                                console.error("Error:", error);
                                alert("發生錯誤: " + error.message);
                            });
                    }
                </script>
            </section>
            <!-- ========================= footer end ========================= -->
            <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
            <link rel="stylesheet" href="assets/css/tiny-slider.css">
            <script src="assets/js/contact-form.js"></script>
            <script src="assets/js/count-up.min.js"></script>
            <script src="assets/js/isotope.min.js"></script>
            <script src="assets/js/glightbox.min.js"></script>
            <script src="assets/js/wow.min.js"></script>
            <script src="assets/js/imagesloaded.min.js"></script>
            <script src="assets/js/main.js"></script>
        </body>

</html>

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