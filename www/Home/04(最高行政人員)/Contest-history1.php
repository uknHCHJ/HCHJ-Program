<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
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
        <title>競賽歷程管理</title>
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
                            <a class="navbar-brand" href="index-04.php">
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
                                        <a href="index-04.php">首頁</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">個人資料</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="contact-04.php">查看個人資料</a></li>
                                            <li class="nav-item"><a href="../changepassword.html">修改密碼</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">班級管理</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="Preparation1-04.php">查看學生備審資料</a></li>
                                            <li class="nav-item"><a href="order1.php">查看志願序</a></li>
                                            <li class="nav-item"><a href="Contest-history1.php">查看競賽歷程</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu">二技校園網</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Schoolnetwork1-04.php">首頁</a></li>
                                        <li class="nav-item"><a href="AddSchool1-04.php">新增校園</a></li>
                                        <li class="nav-item"><a href="SchoolEdit1-04.php">編輯資訊</a></li>                                        
                                        </ul>
                                    </li> 
                                    <li class="nav-item">
                                        <a class="nav-item dd-menu" >比賽資訊</a>           
                                        <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog1-04.php">查看</a></li>
                                            <li class="nav-item"><a href="AddContest1-04.php">新增</a></li>
                                            <li class="nav-item"><a href="ContestEdin1-04.php">編輯</a></li>
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
                                </ul>
                            </div> <!-- navbar collapse -->
                        </nav> <!-- navbar -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        
        </header>
        <!-- ========================= header end ========================= -->
        <!-- ========================= page-banner-section start ========================= -->
        <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="banner-content">
                            <h2 class="text-white">學生競賽歷程管理</h2>
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
                    <span class="wow fadeInDown" data-wow-delay=".2s">班級名單</span>
                </div>
                <select id="grade-select" class="form-select mb-4" onchange="fetchStudentData()">
                    <option value="">請選擇年級...</option>
                    <option value="1">一年級</option>
                    <option value="2">二年級</option>
                    <option value="3">三年級</option>
                    <option value="4">四年級</option>
                    <option value="5">五年級</option>
                </select>  
                <select id="class-select" class="form-select mb-4" onchange="fetchStudentData()">
                    <option value="">請選擇班級...</option>
                    <option value="A">忠</option>
                    <option value="B">孝</option>
                    <option value="C">仁</option>
                    <option value="D">愛</option>
                </select>  
            </div>                                   
        </div>

        <style>
            /* 表格樣式設定 */
            #table-select {
                width: 100%; /* 設定下拉式選單寬度為 100% */
                max-width: 600px; /* 可以根據需要設定最大寬度 */
                margin: 20px auto; /* 讓下拉式選單居中 */
            }
            .table-container {
                max-width: 600px;
                margin: 0 auto; /* 表格居中 */
                padding: 20px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
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
        z-index: 10; /* Place the loading indicator on top of other content */
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
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
        </style>

        <div id="loading">   
            <p>正在載入資料...</p>
        </div>

    <div class="table-container">
        <table id="data-table">
            <thead>
                <tr>    
                    <th>班級</th>
                    <th>學號</th>
                    <th>姓名</th>
                    <th>查看競賽歷程</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

<script>
    function fetchStudentData() {
  // 取得下拉式選單選取的值 (例如：5a 或 5b)
  var selectedGrade = document.getElementById("grade-select").value;
  var selectedClass = document.getElementById("class-select").value;

  // 檢查是否有選取班級
  if (!selectedClass && !selectedGrade) {
        return;
    }


  // 建立 AJAX 請求，向後端 `service-1.php` 獲取資料
  fetch('Contest-history2.php?grade=' + selectedGrade + '&class=' + selectedClass)
    .then(function(response) {
      if (!response.ok) {
        throw new Error('無法取得資料：' + response.statusText);
      }
      return response.json(); // 確認回傳 JSON 格式的資料
    })
    .then(function(data) {
      console.log(data);
      // 更新表格內容 (呼叫另一個函式處理資料更新)
      updateStudentTable(data);
    })
}


function updateStudentTable(data) {
  // 取得表格的 tbody 元素
  var tbody = document.getElementById('data-table').getElementsByTagName('tbody')[0];

  // 清空表格內容
  tbody.innerHTML = '';

  // 遍歷後端回傳的資料，將資料填入表格
  data.forEach(function(item) {
    var row = tbody.insertRow();
    row.insertCell(0).textContent = item.class +"班";
    row.insertCell(1).textContent = item.user;
    row.insertCell(2).textContent = item.name;

    // 新增查看歷程按鈕
    var cell = row.insertCell(3);
    var button = document.createElement('button');
    button.textContent = '查看歷程';
    button.classList.add('btn', 'btn-primary');

    // 設定按鈕點擊事件，跳轉到查看學生歷程的頁面
    button.onclick = function() {
      window.location.href = 'Contest-history-teacher1.php?user=' + item.user;
    };
    cell.appendChild(button);
  });
}
</script>
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
