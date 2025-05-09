<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if ($link) {
  mysqli_query($link, 'SET NAMES UTF8');

} else {
  echo "資料庫連接失敗: " . mysqli_connect_error();
}

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

// 用 mysqli_query 直接撈學校
$sql1 = "SELECT DISTINCT school_id, school_name FROM test";
$res1 = mysqli_query($link, $sql1) or die("SQL 錯誤:".mysqli_error($link));

// 再撈科系
$sql2 = "SELECT DISTINCT department_id, department FROM test ";
$res2 = mysqli_query($link, $sql2) or die("SQL 錯誤:".mysqli_error($link));


?>
<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>新增各科系名額(技優甄審)</title>
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
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">二技校園網</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="Schoolnetwork1-00.php">首頁</a></li>
                                            <li class="nav-item"><a href="Secondtechnicalcampus00-1.php">新增校園科系</a></li>
                                        </ul>
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
                    <h2 class="text-white">新增各科系名額</h2>
                    <div class="page-breadcrumb">
                        <nav aria-label="breadcrumb">
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
                <div class="section-title text-center mb-55">
                    <span class="wow fadeInDown" data-wow-delay=".2s">新增各科系名額</span>
                </div>
            </div>
        </div>


<section id="service" class="service-section pt-130 pb-100">

       <form id="extra-form" action="new-school2.php" method="POST" class="mt-4">
  <!-- 選擇學校 -->
  <div class="form-group mb-3">
    <label for="school-select">選擇學校：</label>
    <select id="school-select" name="school_id" class="form-select" required>
      <option value="">請選擇學校...</option>
      <?php foreach($res1 as $s): ?>
        <option value="<?= $s['school_id'] ?>">
          <?= htmlspecialchars($s['school_name'], ENT_QUOTES) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>

  <!-- 選擇科系 -->
  <div class="form-group mb-3">
    <label for="dept-select">選擇科系：</label>
    <select id="dept-select" name="dept_id" class="form-select" required>
      <option value="">請選擇科系...</option>
      <?php foreach($res2 as $d): ?>
        <option value="<?= $d['department_id'] ?>">
          <?= htmlspecialchars($d['department'], ENT_QUOTES) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <label for="skilled_num">技優甄審招生名額：</label>
  <input type="number" id="skilled_num" name="skilled_num" min="0" placeholder="請輸入技優名額"><br><br>

  <label for="Application_num">申請入學招生名額：</label>
  <input type="number" id="Application_num" name="Application_num" min="0" placeholder="請輸入申請名額"><br><br>

  <button type="submit">送出</button>
</form>

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