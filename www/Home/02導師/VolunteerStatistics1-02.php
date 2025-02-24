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
    <title>志願序總覽</title>
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
                        <h2 class="text-white" style="text-align: left; margin-left: 20px;">志願序總覽</h2>
                        
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f8f9fa;
                text-align: center;
                margin: 20px;
            }

            h1 {
                color: #333;
            }

            table {
                width: 80%;
                margin: 20px auto;
                border-collapse: collapse;
                background: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                overflow: hidden;
            }

            th,
            td {
                padding: 12px;
                border: 1px solid #ddd;
                text-align: center;
            }

            th {
                background-color: #007bff;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #f2f2f2;
            }

            tr:hover {
                background-color: #ddd;
            }

            td.student-name {
                text-align: left;
            }
        </style>
    </head>

    <body>
     

        <table>
            <thead>
                <tr>
                    <th>學校</th>
                    <th>科系</th>
                    <th>人數</th>
                    <th>選擇的學生</th>
                </tr>
            </thead>
            <tbody id="data-body">
                <!-- 資料將由 JavaScript 動態插入 -->
            </tbody>
        </table>

        <script>
            let tableData = [];

            function fetchData() {
                fetch('VolunteerStatistics2-02.php')
                    .then(response => response.json())
                    .then(data => {
                        if (!Array.isArray(data)) {
                            console.error('Unexpected data format:', data);
                            document.getElementById('data-body').innerHTML = '<tr><td colspan="4">No data available</td></tr>';
                            return;
                        }

                        const tableBody = document.getElementById('data-body');
                        tableBody.innerHTML = '';
                        tableData = data;

                        data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                            <td>${row.School}</td>
                            <td>${row.Department}</td>
                            <td>${row.StudentCount}</td>
                            <td class="student-name">${row.Students || '無'}</td>
                        `;
                            tableBody.appendChild(tr);
                        });
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            function exportToExcel() {
                if (tableData.length === 0) {
                    alert("無資料可匯出");
                    return;
                }

                let sheetData = [];
                let schoolRow = ["學校"]; // 第一列：學校名稱
                let departmentRow = ["科系"]; // 第二列：科系名稱
                let maxStudentCount = 0; // 紀錄最多學生數，確保所有學生列數對齊
                let studentRows = [];

                tableData.forEach((row, index) => {
                    // 填入學校與科系資料
                    schoolRow.push(row.School);
                    departmentRow.push(row.Department);

                    let students = row.Students ? row.Students.split(',') : ['無'];
                    maxStudentCount = Math.max(maxStudentCount, students.length);

                    students.forEach((student, studentIndex) => {
                        // 確保 `studentRows` 陣列有足夠的列
                        if (!studentRows[studentIndex]) {
                            studentRows[studentIndex] = Array(tableData.length + 1).fill(''); // 預留空間
                        }
                        // **修正索引**，確保學生姓名對齊學校與科系
                        studentRows[studentIndex][index + 1] = student;
                    });
                });

                // 組合表格資料
                sheetData.push(schoolRow); // 第一列：學校
                sheetData.push(departmentRow); // 第二列：科系

                // 填充學生資料，確保所有學生列數對齊
                for (let i = 0; i < maxStudentCount; i++) {
                    sheetData.push(studentRows[i] || Array(tableData.length + 1).fill(''));
                }

                let ws = XLSX.utils.aoa_to_sheet(sheetData);
                let wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "志願統計");
                XLSX.writeFile(wb, "志願選擇統計.xlsx");
            }
            fetchData();
        </script>
    </body>
    <button class="export-btn" onclick="exportToExcel()">📊 匯出 Excel</button>

    <style>
        .export-btn {
            background: linear-gradient(135deg, #007bff, #0056b3);
            /* 藍色漸變 */
            color: white;
            font-size: 16px;
            font-weight: bold;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            margin-bottom: 20px;
            /* ✅ 調整底部間距，避免貼著下方區塊 */
            display: inline-block;
            /* 讓按鈕不會占滿整行 */
        }

        .export-btn:hover {
            background: linear-gradient(135deg, #0056b3, #004494);
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
            transform: scale(1.05);
        }

        .export-btn:active {
            transform: scale(0.95);
            box-shadow: none;
        }
    </style>


    </html>




    </script>
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