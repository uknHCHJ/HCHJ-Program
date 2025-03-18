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
                                    <a class="page-scroll" href="index-01.php">首頁</a>
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
                                        <li class="nav-item"><a href="/~HCHJ/Home/optional_write1.php">選填志願</a>
                                        </li>
                                        <li class="nav-item"><a href="/~HCHJ/Home/optional_show1.php">查看志願序</a>
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
        // 抓取網址上面的 user 值並顯示在頁面上
        document.addEventListener('DOMContentLoaded', function () {
            var urlParams = new URLSearchParams(window.location.search);
            var userValue = urlParams.get('user');
        });
    </script>
    <!-- ========================= page-banner-section end ========================= -->

    <!-- ========================= service-section start ========================= -->
    <section id="service" class="service-section pt-130 pb-100">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                    <div class="section-title text-center mb-55">
                        <span class="wow fadeInDown" data-wow-delay=".2s">查看志願序</span>
                    </div>
                </div>
            </div>

            <style>
                /* 表格樣式設定 */
                #table-select {
                    width: 100%;
                    margin: 20px auto;
                    table-layout: fixed;
                    /* 固定表格寬度，讓欄位自動調整 */
                }

                /* 表格容器的樣式調整 */
                .table-container {
                    width: 100%;
                    max-width: 100 %;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: white;
                    justify-content: center;
                    align-items: center;
                    flex-direction: column;
                    border-radius: 10px;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                    margin-top: -50px;
                    overflow: hidden;
                    animation: fadeIn 1s ease-in-out;
                    
                    /* 確保表格區塊與底部區塊之間有間距 */
                }

                /* 確保底部區塊不受影響 */
                .footer {
                    clear: both;
                    /* 清除浮動影響 */
                }


                table {
                    width: 100%;
                    border-collapse: collapse;
                    table-layout: auto;
                    /* 讓表格自動調整每一列的寬度 */
                }

                th,
                td {
                    min-width: 150px;
                    /* 設定欄位的最小寬度 */
                    max-width: 250px;
                    /* 設定欄位的最大寬度 */
                    padding: 10px;
                    border: 1px solid #ccc;
                    text-align: center;
                    overflow: hidden;
                    /* 防止文字溢出 */
                    text-overflow: ellipsis;
                    /* 溢出文字顯示省略號 */
                    white-space: nowrap;
                    /* 讓文字不換行 */
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

                #loading {
                    display: none;
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.5);
                    z-index: 10;
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

                .action-buttons {
                    display: flex;
                    justify-content: center;
                    gap: 10px;
                    margin-top: 20px;
                }

                .action-buttons button {
                    padding: 5px 10px;
                    font-size: 14px;
                    border-radius: 5px;
                }
            </style>


            <div id="loading">
                <p>正在載入資料...</p>
            </div>
            <div class="table-container">
                <table id="data-table">
                    <thead>
                        <tr>
                            <th>志願序號</th>
                            <th>學校名稱</th>
                            <th>學校科系</th>
                            <th>上傳時間</th>
                            <th>相同志願人數</th>
                            <th id="edit-header" style="display: none;">變更順序</th> <!-- 編輯模式下顯示 -->
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var urlParams = new URLSearchParams(window.location.search);
                    var userValue = urlParams.get('user');

                    document.getElementById('loading').style.display = 'flex';

                    fetch('optional_show2.php?user=' + encodeURIComponent(userValue))
                        .then(response => response.json())
                        .then(data => {
                            window.studentData = data;
                            window.isEditing = false;
                            window.isDataChanged = false;

                            renderTable(window.studentData);
                            document.getElementById('loading').style.display = 'none';
                        })
                        .catch(error => {
                            console.error('錯誤:', error);
                            document.getElementById('loading').style.display = 'none';
                        });

                    window.addEventListener('beforeunload', function (event) {
                        if (window.isDataChanged) {
                            event.preventDefault();
                            event.returnValue = '您有未保存的變更，確定要離開嗎？';
                        }
                    });
                });

                function enableEditMode(button) {
                    if (window.isEditing) return;
                    window.isEditing = true;
                    renderTable(window.studentData);

                    button.disabled = true;
                    document.getElementById('saveChangesButton').style.display = 'inline-block';
                }

                function renderTable(data) {
                    var table = document.getElementById('data-table');
                    var tbody = table.getElementsByTagName('tbody')[0];

                    tbody.innerHTML = '';

                    document.getElementById('edit-header').style.display = window.isEditing ? '' : 'none';

                    if (data.length === 0 || data[0] === "查無資料") {
                        var row = tbody.insertRow();
                        var cell = row.insertCell(0);
                        cell.colSpan = 7;
                        cell.textContent = '您還未填選志願';
                        cell.style.textAlign = 'center';
                        cell.style.color = 'gray';
                        return;
                    }

                    data.forEach(function (item, index) {
                        var row = tbody.insertRow();

                        row.insertCell(0).textContent = index + 1;
                        row.insertCell(1).textContent = item.school_name;
                        row.insertCell(2).textContent = item.department_name;
                        row.insertCell(3).textContent = item.time;
                        row.insertCell(4).textContent = item.student_count || '0';

                        if (window.isEditing) {
                            var editCell = row.insertCell(5);
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

                            var deleteCell = row.insertCell(6);
                            var deleteButton = document.createElement('button');
                            deleteButton.textContent = '刪除';
                            deleteButton.className = 'btn btn-sm btn-danger';
                            deleteButton.onclick = function () {
                                deleteItem(index, item);
                            };
                            deleteCell.appendChild(deleteButton);
                        }
                    });
                }

                function deleteItem(index, item) {
                    if (confirm("確定要刪除此志願嗎？")) {
                        fetch('delete_show2.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({
                                preference_rank: item.preference_rank,
                                school_name: item.school_name,
                                department_name: item.department_name
                            })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    window.studentData.splice(index, 1);
                                    window.isDataChanged = true;
                                    renderTable(window.studentData);
                                } else {
                                    alert('刪除失敗: ' + data.message);
                                }
                            })
                            .catch(error => {
                                console.error('刪除失敗:', error);
                                alert('刪除失敗，請稍後再試。');
                            });
                    }
                }

                function moveItem(index, direction) {
                    var newIndex = index + direction;

                    if (newIndex >= 0 && newIndex < window.studentData.length) {
                        var temp = window.studentData[index];
                        window.studentData[index] = window.studentData[newIndex];
                        window.studentData[newIndex] = temp;

                        window.studentData[index].preference_rank = index + 1;
                        window.studentData[newIndex].preference_rank = newIndex + 1;

                        window.isDataChanged = true;
                        renderTable(window.studentData);
                    }
                }

                function saveChanges() {
                    fetch('optional_update.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            preferences: window.studentData.map(item => ({
                                preference_rank: item.preference_rank,
                                school_name: item.school_name,
                                department_name: item.department_name
                            }))
                        })
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            alert('變更已保存！');

                            document.getElementById('saveChangesButton').style.display = 'none';
                            window.isEditing = false;
                            document.getElementById('editButton').disabled = false;
                            renderTable(window.studentData);
                        })
                        .catch((error) => {
                            console.error('保存失敗:', error);
                            alert('變更保存失敗，請稍後再試。');
                        });
                }
            </script>

            <!-- 按鈕區 -->
            <div style="text-align: center; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" style="background-color:#4CAF50; color: white;"
                    onclick="window.location.href='optional_write1.php';">
                    返回上一頁
                </button>
                <button id="editButton" type="button" class="btn btn-secondary"
                    style="background-color:#4CAF50; color: white;" onclick="enableEditMode(this);">
                    編輯
                </button>
                <button id="saveChangesButton" type="button" class="btn btn-secondary"
                    style="background-color:#4CAF50; color: white; display: none;" onclick="saveChanges();">
                    保存變更
                </button>
            </div>


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
            <!-- ========================= client-logo-section end ========================= -->



            <!-- ========================= footer start ========================= -->
            <footer class="footer pt-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                                <a href="index-01.php" class="logo mb-30"><img src="schoolimages/uknlogo.png"
                                        alt="logo"></a>
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


            <!-- ========================= 卷軸 ========================= -->
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