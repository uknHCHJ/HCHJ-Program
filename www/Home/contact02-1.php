<?php
session_start();
include 'db.php';
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$image = $userData['image'];
$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);
?>
<!Doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>個人資料</title>
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
                                    <a class="nav-item dd-menu" href="student02-1.php">學生管理</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序統計</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02(2).php">繳交志願序統計</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">二技校園網</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Schoolnetwork1-02.php">首頁</a></li>
                                        <li class="nav-item"><a href="AddSchool1-02.php">新增校園</a></li>
                                        <li class="nav-item"><a href="SchoolEdit1-02.php">編輯詳細資料</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">比賽資訊</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="Contestblog-02.php">查看</a></li>
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




  <!-- ========================= 橫幅(大標題) start ========================= -->
  <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
          <div class="banner-content">
            <h2 class="text-white">個人資料</h2>

          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========================= 橫幅(大標題) end ========================= -->
  <style>
    /* 按鈕樣式 */
    .button-image {
      width: 300px;
      /* 設定按鈕大小 */
      height: 200px;
      cursor: pointer;
      /* 滑鼠移上去變成點擊樣式 */
      border: none;
      outline: none;
    }

    /* 懸停效果 */
    .button-image:hover {
      opacity: 0.8;
      /* 懸停時變透明一點 */
      transform: scale(1.05);
      /* 稍微放大 */
    }
  </style>
  <!-- ========================= 資料旁三個方框 start ========================= -->
  <section class="contact-section pt-130">
    <div class="container">
      <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-xl-8">
          <div class="contact-form-wrapper">
            <div class="row">
              <div class="col-xl-10 col-lg-8 mx-auto">
                <div class="section-title text-center mb-50">
                  <div class="header-section">
                    <div class="continer">
                      <?php
                      session_start();
                      // 資料庫連接參數
                      $servername = "127.0.0.1";
                      $username = "HCHJ";
                      $password = "xx435kKHq";
                      $dbname = "HCHJ";

                      // 建立資料庫連接
                      $conn = new mysqli($servername, $username, $password, $dbname);
                      if ($conn->connect_error) {
                        die("連接失敗: " . $conn->connect_error);
                      }

                      // 確保 SESSION 中有儲存唯一識別符
                      if (isset($_SESSION['user'])) {
                        $userId = $_SESSION['user']['user'];
                      } else {
                        die("未登入，無法顯示圖片。");
                      }

                      // 從資料庫中提取用戶圖片
                      $imageData = null;
                      $sql = "SELECT `image` FROM `user` WHERE `user` = ?";
                      $stmt = $conn->prepare($sql);
                      $stmt->bind_param("s", $userId);
                      $stmt->execute();
                      $stmt->bind_result($imageData);
                      $stmt->fetch();
                      $stmt->close();

                      $conn->close();
                      ?>

                      <!-- HTML 和 JavaScript -->
                      <form enctype="multipart/form-data" action="/~HCHJ/Home/contact02-2.php" method="POST"
                        style="height: 200px; max-width: 1000px; margin: auto">
                        <!-- 隱藏的檔案選擇框 -->
                        <input type="file" id="file-input" name="image" accept=".jpg,.jpeg,.png" style="display: none;"
                          onchange="previewAndConfirm(event)">

                        <!-- 點擊圖片選擇檔案 -->
                        <button type="button" id="button-image"
                          onclick="document.getElementById('file-input').click();">
                          <img src="data:image/jpeg;base64,<?= base64_encode($imageData) ?>" alt="button-image"
                            style="cursor: pointer; width: 150px; height: 150px;">
                        </button>
                        <p  style="font-size: 16px;" class="wow fadeInUp" data-wow-delay=".2s">點擊圖片更換頭貼</p>
                        <!-- 隱藏的提交按鈕 -->
                        <input type="submit" value="上傳圖片" style="display:none;" id="submit-button">
                      </form>

                      <script>
                        function previewAndConfirm(event) {
                          var file = event.target.files[0];
                          if (file) {
                            // 顯示確認視窗
                            var confirmChange = confirm("是否更換新頭貼？");

                            if (confirmChange) {
                              // 顯示選擇的圖片作為預覽
                              var reader = new FileReader();
                              reader.onload = function (e) {
                                var img = document.getElementById('button-image').getElementsByTagName('img')[0];
                                img.src = e.target.result; // 更新圖片源為選擇的圖片
                              }
                              reader.readAsDataURL(file);

                              // 提交表單
                              setTimeout(function () {
                                document.forms[0].submit();
                              }, 500); // 延遲提交，以確保預覽完成
                            } else {
                              // 如果用戶取消，清除選擇的檔案
                              document.getElementById('file-input').value = "";
                            }
                          }
                        }
                      </script>


                    </div>
                  </div>
                  <h2 class="wow fadeInUp" data-wow-delay=".4s"><?php echo $userData['name']; ?>老師
                    
                  </h2>

                </div>
              </div>
            </div>
            <form action="assets/php/mail.php" class="contact-form">
              <div class="row">
                <div class="col-md-6">

                  <p class="wow fadeInUp" data-wow-delay=".2s">
                    科系：<?php echo $userData['department']; ?></p>
                </div>
                <div class="col-md-6">
                  <p class="wow fadeInUp" data-wow-delay=".2s">
                    <?php
                    session_start();
                    include 'db.php';
                    // 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
                    $userData = $_SESSION['user'];
                    // 例如從 SESSION 中獲取 user_id
                    $username = $userData['name'];
                    $user = $userData['user'];
                    $sql = "SELECT * FROM `user` WHERE `name` ='$username' AND `user`='$user'";
                    $result = mysqli_query($link, $sql);
                    if($result){
                      $grade=[];
                      $calss = [];  // 用於儲存符合條件的班導名字
                      if($row = mysqli_fetch_assoc($result)){
                        $grade = explode(',', $row['grade']);
                        $class = explode(',', $row['class']);
                      }
                      if (count($grade) < 2) {
                        $grade[] = '無';  // 若符合條件的班導少於兩位，新增 "無" 作為占位
                        
                    }
                    }
                    ?>
                    代班班級：<?php echo $grade[0], $class[0]; ?>
                  </p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <p class="wow fadeInUp" data-wow-delay=".2s">
                    第二代班班級：<?php echo $grade[1], $class[1]; ?>
                </div>
                <div class="col-md-6">
                  <p class="wow fadeInUp" data-wow-delay=".2s">帳號名稱：<?php echo $userData['user']; ?>
                  </p>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================= 資料旁三個方框 end ========================= -->


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