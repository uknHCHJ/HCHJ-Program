<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.php");
    exit();
}

$userData = $_SESSION['user']; //

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id 

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>備審紀錄</title>
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
                                    <a class="nav-item dd-menu">查看個人資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a href="/~HCHJ/changepassword-01.html">修改密碼</a>
                                        </li>
                                        <li class="nav-item active"><a href="/~HCHJ/Home/contact01-1.php">個人資料</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">備審資料</a>
                                    <ul class="sub-menu">

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
                                    <a class="nav-item dd-menu">志願序</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item active"><a
                                                href="/~HCHJ/Home/optional(填選志願1)-01.php">選填志願</a></li>
                                        <li class="nav-item active"><a href="/~HCHJ/Home/optional(志願顯示).php">編輯</a></li>
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
                        <h2 class="text-white">備審紀錄</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">學生競賽歷程</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= page-banner-section end ========================= -->

    <!-- ========================= service-section start ========================= -->
    <section id="service" class="service-section pt-130 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                    <div class="section-title text-center mb-55">
                        <span class="wow fadeInDown" data-wow-delay=".2s">備審下載</span>
                    </div>
                </div>
            </div>

            <style>
                .button-container {
                    display: flex;
                    /* 使用彈性盒子模型 */
                    gap: 10px;
                    /* 設定按鈕間的間距 */
                }

                /* 刪除按鈕樣式 */
                .delete-button {
                    background-color: #f44336;
                    /* 紅色背景 */
                    color: white;
                    /* 白色字體 */
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    display: inline-block;
                }

                .delete-button:hover {
                    background-color: #d32f2f;
                    /* 滑鼠懸停時變成稍微深一點的紅色 */
                }

                .delete-button:active {
                    transform: scale(0.98);
                }

                /* 下載按鈕樣式 */
                .download-button {
                    background-color: #4CAF50;
                    /* 綠色背景 */
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    display: inline-block;
                }

                .download-button:hover {
                    background-color: #45a049;
                    /* 滑鼠懸停時變成稍微深一點的綠色 */
                }

                .download-button:active {
                    transform: scale(0.98);
                }

                .button-container {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                    /* 調整空間大小 */
                }

                /* 表格樣式設定 */
                #table-select {
                    width: 7000px;
                    /* 設定下拉式選單寬度為 100% */
                    max-width: 7000px;
                    /* 可以根據需要設定最大寬度 */
                    margin: 20px auto;
                    /* 讓下拉式選單居中 */
                }

                .table-container {
                    max-width: 600px;
                    margin: 0 auto;
                    /* 表格居中 */
                    padding: 20px;
                    background-color: white;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                }

                th,
                td {
                    padding: 10px;
                    border: 1px solid #ccc;
                    text-align: center;
                }

                th {
                    background-color: #6A7C92;
                    color: white;
                }

                td {
                    background-color: #f9f9f9;
                }

                /* 表格淡入動畫 */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(-20px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .table-container {
                    animation: fadeIn 1s ease-in-out;
                }

                /* ... (other styles) */

                #loading {
                    display: none;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 10;
                    /* Place the loading indicator on top of other content */
                    justify-content: center;
                    align-items: center;
                }

                #loading:before {
                    content: "";
                    display: inline-block;
                    border-radius: 4px;
                    width: 100px;
                    height: 100px;
                    border: 6px solid #f3f3f3;
                    border-color: #f3f3f3 transparent #f3f3f3 transparent;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% {
                        transform: rotate(0deg);
                    }

                    100% {
                        transform: rotate(360deg);
                    }
                }

                /* 設定欄位寬度   上傳次數 */
                #data-table th:nth-child(1) {
                    width: 900px;
                }

                /* 日期 */
                #data-table th:nth-child(2) {
                    width: 600px;
                }

                /* 檔名 */
                #data-table th:nth-child(3) {
                    width: 3000px;
                }

                /* 下載備審 */
                #data-table th:nth-child(4) {
                    width: 1200px;
                }

                /* 刪除備審 */
                #data-table th:nth-child(5) {
                    width: 1200px;
                }
            </style>

            <div id="loading">  
                <p>正在載入資料...</p>
            </div>

            <div class="table-container">
                <table id="data-table">
                    <thead>
                        <tr>
                            <th>上傳次數</th>
                            <th>日期</th>
                            <th>檔名</th>
                            <th>下載備審</th>
                            <th>刪除備審</th>
                        </tr>
                    </thead>
                    <?php
                    $sql = "SELECT * FROM file ORDER BY id DESC";
                    $result = mysqli_query($link, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>';
                        echo '<td>第' . $row['id'] . '次</td>';
                        echo '<td>' . $row['upload_date'] . '</td>';
                        echo '<td>' . $row['name'] . '</td>';
                        //echo "<td><a href='recordforreview-01(備審紀錄後端).php?file_id=" . urlencode($row['id']) . "'>下載</a></td>";
                    
                        echo '<td>
                        <form action="recordforreview01-2.php" method="POST">
                       <input type="hidden" name="file_id" value="' . $row['id'] . '">
                        <button type="submit" class="download-button">下載檔案</button>
                        </form>
                        </td>';
                        echo '<td>
                        <form action="delete-recordforreview-01.php" method="POST" onsubmit="return confirmDeletion(this)">
                         <input type="hidden" name="confirm_delete" value="">
                       <input type="hidden" name="file_id" value="' . $row['id'] . '">
                        <button type="submit" class="delete-button">刪除檔案</button>
                        </form>
                        </td>';

                        echo '</tr>';

                    }

                    ?>
                    <script>
                        // 函數用來顯示兩次確認的彈出視窗
                        function confirmDeletion(form) {
                            // 第一層確認
                            if (confirm("確定要刪除此檔案嗎？")) {
                                // 第二層確認
                                if (confirm("刪除後無法復原，是否仍要繼續？")) {
                                    // 設定隱藏欄位 confirm_delete 為 'yes'
                                    form.confirm_delete.value = 'yes';
                                    return true; // 提交表單
                                }
                            }
                            return false; // 如果取消，則阻止表單提交
                        }
                    </script>

                    <tbody>

                    </tbody>

                </table>
                <?php
                // 確定當前頁面，默認為第 1 頁
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; //將 page 參數轉換為整數
                $records_per_page = 15; // 每頁顯示 10 筆資料
                $offset = ($page - 1) * $records_per_page; //參數沒有傳遞（即 isset() 返回 false），則會使用 1 作為預設值，這意味著當用戶沒有指定頁數時，會顯示第 1 頁
                $total_pages = ceil($total_records / $records_per_page);//假設總記錄數是 53 條，每頁顯示 10 條記錄：53 / 10 = 5.3。使用 ceil() 之後，會變成 6，也就是總頁數為 6。
                ?>

                <!-- 分頁 -->
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <!-- 上一頁按鈕，當前頁為1時禁用 -->
                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <!-- 分頁顯示：動態生成每一頁的頁碼 -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <!-- 下一頁按鈕，當前頁為最後一頁時禁用 -->
                        <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <div class="button-container">
                    <button type="submit" class="download-button"
                        onclick="window.location.href='/~HCHJ/Home/upload01-1php';">新增備審</button>
                    <button type="submit" class="download-button"
                        onclick="window.location.href='/~HCHJ/Home/upload01-1php';">匯出備審</button>
                </div>
            </div>
</body>
</div>
</div>

</section>
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