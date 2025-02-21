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
$username=$userData['name']; 
?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>新增成員</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
    <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
    <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/tiny-slider.css">
    <link rel="stylesheet" href="assets/css/glightbox.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

     <!-- ========================= header start ========================= -->
     <header class="header navbar-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="index-00.php">
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
                                        <a href="index-00.php">首頁</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="contact-00.php">個人資料</a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="../changepassword.html">修改密碼</a>
                                    </li>  
                                    <li class="nav-item">
                                        <a href="Adduser.php">新增人員</a>
                                    </li>        
                                    <li class="nav-item">
                                        <a href="Access-Control1.php">權限管理</a>                                
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

    <!-- 標題區塊 -->
<section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg'); height: 250px; background-size: cover; background-position: center;">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="banner-content">
                    <h2 class="text-white">新增成員</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item" aria-current="page"><a href="index-00.php">首頁</a></li>
                                <li class="breadcrumb-item active" aria-current="page">新增成員</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- 新增成員選項 -->
<section id="service" class="service-section pt-130 pb-100">
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                <div class="section-title text-center mb-55">
                    <span class="wow fadeInDown" data-wow-delay=".2s">新增成員</span>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="form-group mb-3">
                <label for="action-select">選擇新增方式：</label>
                <select id="action-select" class="form-control" onchange="toggleUploadForm()">
                    <option value="upload">上傳 Excel 文件</option>
                    <option value="manual">手動新增成員</option>
                </select>
            </div>

            <div id="upload-section">
                <div class="form-group mb-3">
                    <form id="upload-form" action="uploadAdduser.php" method="POST" enctype="multipart/form-data">
                        <label for="excel-file">選擇 Excel 文件：</label>
                        <input type="file" id="excel-file" name="excel_file" class="form-control" accept=".xls,.xlsx">
                        <button type="submit" class="btn btn-primary">送出</button>
                    </form>
                </div>
            </div>

            <div id="manual-section" style="display: none;">
                <form id="manual-form" action="uploadAdduser.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="department">選擇科系：</label>
                        <select id="department" name="department" class="form-select">
                            <option value="護理科">護理科</option>
                            <option value="資訊管理科">資訊管理科</option>
                            <option value="企業管理科">企業管理科</option>
                            <option value="幼兒保育科">幼兒保育科</option>
                            <option value="視光科">視光科</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="user">帳號(學生為學號)：</label>
                        <input type="text" id="user" name="user" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="grade">選擇年級：</label>
                        <select id="grade" name="grade" class="form-select">
                            <option value="1">一年級</option>
                            <option value="2">二年級</option>
                            <option value="3">三年級</option>
                            <option value="4">四年級</option>
                            <option value="5">五年級</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="class">選擇班級：</label>
                        <select id="class" name="class" class="form-select">
                            <option value="A">忠</option>
                            <option value="B">孝</option>
                            <option value="C">仁</option>
                            <option value="D">愛</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="name">姓名：</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>

                    <div class="form-group mb-3">
                        <label for="permission">選擇權限：</label>
                        <select id="permissions" name="permissions" class="form-select">
                            <option value="9">請選擇...</option>
                            <option value="1">學生</option>
                            <option value="2">班級導師</option>
                            <option value="4">最高行政人員</option>
                            <option value="0">管理員</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="permission2">選擇第二權限(如果沒有可不選)：</label>
                        <select id="permissions2" name="permissions2" class="form-select">
                            <option value="9">請選擇...</option>
                            <option value="1">學生</option>
                            <option value="2">班級導師</option>
                            <option value="4">最高行政人員</option>
                            <option value="0">管理員</option>
                        </select>
                    </div>

                    <button id="manual-submit" type="submit" class="btn btn-primary">送出</button>
                </form>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="download_excel.php" class="btn btn-success" download>下載範例 Excel 檔案</a>
        </div>
    </div>
</section>

<!-- 引入JS -->
<script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
<script>
function toggleUploadForm() {
    var select = document.getElementById('action-select');
    var uploadSection = document.getElementById('upload-section');
    var manualSection = document.getElementById('manual-section');

    if (select.value === 'upload') {
        uploadSection.style.display = 'block';
        manualSection.style.display = 'none';
    } else {
        uploadSection.style.display = 'none';
        manualSection.style.display = 'block';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    toggleUploadForm();
});
</script>

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
                            <a href="index-00.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
</body>
</html>