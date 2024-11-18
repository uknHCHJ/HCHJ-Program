<?php
include("optional(填選志願2)-01.php");
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>二技志願填選</title>
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
                        <a class="navbar-brand" href="index-01.html">
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

                            </ul>
                        </div> <!-- navbar collapse -->
                    </nav> <!-- navbar -->
                </div>
            </div> <!-- row -->
        </div> <!-- container -->

    </header>
    <!-- ========================= header end ========================= -->

    <!-- ========================= page-banner-section start ========================= -->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>二技志願填選</title>
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


        <style>
            body {
                background-color: #f0f8ff;
                font-family: 'Arial', sans-serif;
                color: #333;
            }

            h2 {
                color: #0044cc;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .form-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }

            .form-content {
                margin-top: 130px;
                /* 設定上方外邊距為 130 像素，以確保該元素與上方內容有足夠的距離。 */

                text-align: center;
                /* 將該元素內的文字水平置中對齊。 */

                background-color: #ffffff;
                /* 設定背景顏色為白色 (#ffffff)。 */

                padding: 20px;
                /* 設定內邊距為 20 像素，讓內容與邊框之間有一些空間。 */

                border-radius: 15px;
                /* 設定邊框的圓角半徑為 15 像素，使邊框的四個角呈圓形。 */

                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                /* 設定陰影效果，水平方向位移 0 像素，垂直方向位移 4 像素，模糊半徑為 8 像素，顏色為 rgba(0, 0, 0, 0.1) (黑色，透明度 10%)，使元素看起來有些浮起來。 */

                width: 30%;
                /* 設定元素的寬度為父容器的 30%。 */

                margin-bottom: 20px;
                /* 設定下方外邊距為 20 像素，讓該元素與下方內容之間有一些空間。 */
            }

            label {
                display: block;
                margin-top: 15px;
                font-size: 16px;
                color: #333;
            }

            select,
            input {
                width: 60%;
                padding: 10px;
                < !--下拉式的高-->margin-top: 10px;
                border-left: 2px solid #ccc;
                border-right: 2px solid #ccc;
                border-radius: 5px;
                < !--設定邊框圓角-->border: 1px solid #ccc;
                font-size: 14px;
                background-color: #f9f9f9;
            }

            select:focus,
            input:focus {
                border-color: #0044cc;
                outline: none;
            }

            button {
                background-color: #0044cc;
                color: #fff;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                margin-top: 20px;
                cursor: pointer;
            }

            button:hover {
                background-color: #003399;
            }

            .choice-row {
                width: 100%;
                display: flex;
                justify-content: space-between;
            }

            .choice {
                width: 48%;
            }

            .form-container.center {
                justify-content: center;
            }

            .form-content.center {
                max-width: 500px;
                margin-bottom: 0;
            }

            center {
                margin-top: 20px;
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
        <!-- ========================= header end ========================= -->

        <!-- ========================= form section start ========================= -->
        <div class="form-container">
            <div class="form-content">
                <h2>志願一</h2>
                <form id="school-form">
                    <label for="school">學校名稱:</label>
                    <select id="school" name="school">
                        <option value="">請選擇學校</option>
                        <?php choose(); ?>
                    </select>
                    <label for="department">科系名稱:</label>
                    <select id="department" name="department">
                        <option value="">請先選擇學校</option>
                    </select>
                </form>
            </div>

            <div class="form-content">
                <h2>志願二</h2>
                <form id="school2-form">
                    <label for="school2">學校名稱:</label>
                    <select id="school2" name="school2">
                        <option value="">請選擇學校</option>
                        <?php choose(); ?>
                    </select>
                    <label for="department2">科系名稱:</label>
                    <select id="department2" name="department2">
                        <option value="">請選擇科系</option>
                    </select>
                </form>
            </div>

            <div class="form-content">
                <h2>志願三</h2>
                <form id="school3-form">
                    <label for="school3">學校名稱:</label>
                    <select id="school3" name="school3">
                        <option value="">請選擇學校</option>
                        <?php choose(); ?>
                    </select>
                    <label for="department3">科系名稱:</label>
                    <select id="department3" name="department">
                        <option value="">請選擇科系</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="form-container">
            <div class="form-content">
                <h2>志願四</h2>
                <form id="school4-form">
                    <label for="school4">學校名稱:</label>
                    <select id="school4" name="school4">
                        <option value="">請選擇學校</option>
                        <?php choose(); ?>
                    </select>
                    <label for="department4">科系名稱:</label>
                    <select id="department4" name="department4">
                        <option value="">請選擇科系</option>
                    </select>
                </form>
            </div>

            <div class="form-content">
                <h2>志願五</h2>
                <form id="school5-form">
                    <label for="school5">學校名稱:</label>
                    <select id="school5" name="school5">
                        <option value="">請選擇學校</option>
                        <?php choose(); ?>
                    </select>
                    <label for="department5">科系名稱:</label>
                    <select id="department5" name="department5">
                        <option value="">請選擇科系</option>
                    </select>
                </form>
            </div>
        </div>

        <center><button type="submit">提交志願</button></center>

        <!-- ========================= script start ========================= -->
        
        <script>
            $(document).ready(function () {
    // 針對每一個學校選單都添加 change 事件的處理
    $('select[id^="school"]').change(function () {
        var $school_ID = $(this).val(); // 取得選中的學校名稱
        var $departmentSelect = $(this).closest('.form-content').find('select[id^="department"]');

        if ($school_ID) {
            // 發送 AJAX 請求到 PHP 端
            $.ajax({
                type: 'POST',
                url: 'optional(填選志願2)-01.php',
                data: { id: $school_ID },
                dataType: 'json',
                success: function (response) {
                    $departmentSelect.html(''); // 清空之前的選項

                    if (response.length > 0) {
                        $.each(response, function (index, department) {
                            $departmentSelect.append(new Option(department.department_name, department.ID));
                        });
                        $departmentSelect.prop('disabled', false); // 啟用科系選單
                        <option value="">請選擇科系</option>
                    } else {
                        $departmentSelect.append(new Option('無對應科系', ''));
                        $departmentSelect.prop('disabled', true); // 禁用科系選單
                    }
                }
                error: function (xhr, status, error) {
    console.error("發生錯誤:", status, error); // 錯誤詳情
    alert("發生錯誤，請檢查控制台以獲取更多信息。");
}

                /*error: function () {
                    console.error("發生錯誤");
                }*/
            });
        } else {
            $departmentSelect.html('<option value="">請先選擇學校</option>');
            $departmentSelect.prop('disabled', true); // 禁用科系選單
        }
        
    });
});



    </script>
    </body>

</html>

<!-- ========================= client-logo-section start ========================= -->
<section class="client-logo-section pt-100 pb-130">
    <div class="container">
        <div class="client-logo-wrapper">
            <div class="client-logo-carousel d-flex align-items-center justify-content-between">
                <div class="client-logo">
                    <img src="assets/img/client-logo/uideck-logo.svg" alt="">
                </div>
                <div class="client-logo">
                    <img src="assets/img/client-logo/pagebulb-logo.svg" alt="">
                </div>
                <div class="client-logo">
                    <img src="assets/img/client-logo/lineicons-logo.svg" alt="">
                </div>
                <div class="client-logo">
                    <img src="assets/img/client-logo/graygrids-logo.svg" alt="">
                </div>
                <div class="client-logo">
                    <img src="assets/img/client-logo/lineicons-logo.svg" alt="">
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
                    <a href="index.html" class="logo mb-30"><img src="assets/img/logo/logo.svg" alt="logo"></a>
                    <p class="mb-30 footer-desc">We Crafted an awesome desig library that is
                        robust and intuitive to
                        use. No matter you're building a business presentation websit.</p>
                </div>
            </div>
            <div class="col-xl-2 offset-xl-1 col-lg-2 col-md-6">
                <div class="footer-widget mb-60 wow fadeInUp" data-wow-delay=".4s">
                    <h4>Quick Link</h4>
                    <ul class="footer-links">
                        <li>
                            <a href="javascript:void(0)">Home</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">About Us</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Service</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Contact</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="footer-widget mb-60 wow fadeInUp" data-wow-delay=".6s">
                    <h4>Service</h4>
                    <ul class="footer-links">
                        <li>
                            <a href="javascript:void(0)">Marketing</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Branding</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Web Design</a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">Graphics Design</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6">
                <div class="footer-widget mb-60 wow fadeInRight" data-wow-delay=".8s">
                    <h4>Contact</h4>
                    <ul class="footer-contact">
                        <li>
                            <p>+00983467367234</p>
                        </li>
                        <li>
                            <p>yourmail@gmail.com</p>
                        </li>
                        <li>
                            <p>United State Of America
                                *12 Street House</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="copyright-area">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="footer-social-links">
                        <ul class="d-flex">
                            <li><a href="javascript:void(0)"><i class="lni lni-facebook-filled"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="lni lni-twitter-filled"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="lni lni-linkedin-original"></i></a></li>
                            <li><a href="javascript:void(0)"><i class="lni lni-instagram-filled"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <p class="wow fadeInUp" data-wow-delay=".3s"><a target="_blank" href="http://www.mobanwang.com/"
                            title="网页模板">网页模板</a></p>
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