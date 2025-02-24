<?php
session_start();
include 'db.php';
// 確保你在 SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userData = $_SESSION['user'];
// 例如從 SESSION 中獲取 user_id
$userId = $userData['user'];
$name = $userData['name'];
$image = $userData['image'];
$query = sprintf("SELECT user FROM `user` WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);
//if (mysqli_num_rows($result) > 0) {
// $userDetails = mysqli_fetch_assoc($result);  
//} else {
// echo "找不到使用者的詳細資料";
//}
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
                                    <a class="nav-item dd-menu">學生管理</a>
                                    <ul class="sub-menu">
                                    <li class="nav-item"><a href="student02-1.php">學生備審管理</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序總覽</a></li>
                                        <li class="nav-item"><a href="VolunteerStatistics1-02(2).php">繳交志願序</a></li>
                                        <li class="nav-item"><a href="settime02-1.php">志願序開放時間</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="Schoolnetwork1.php">二技校園網</a>
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
    /* 讓圖片變成圓形 */
    .button-image {
      width: 150px;
      /* 設定圖片的寬度 */
      height: 150px;
      /* 設定圖片的高度，確保是正方形 */
      border-radius: 50%;
      /* 讓圖片變圓 */
      object-fit: cover;
      /* 確保圖片填滿圓形範圍，不變形 */
      border: 2px solid #000;
      /* 可選的邊框 */
    }

    .button-image {
      width: 150px;
      /* 設定按鈕大小，確保寬高相等 */
      height: 150px;
      cursor: pointer;
      /* 滑鼠移上去變成點擊樣式 */
      border: none;
      outline: none;
      border-radius: 50%;
      /* 讓按鈕變成圓形 */
      overflow: hidden;
      /* 防止圖片超出圓形邊界 */
      display: flex;
      /* 讓內部圖片置中 */
      justify-content: center;
      align-items: center;
    }

    /* 懸停效果 */
    .button-image:hover {
      opacity: 0.8;
      /* 懸停時變透明一點 */
      transform: scale(1.05);
      /* 稍微放大 */
    }

    .button-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      /* 確保圖片不會變形，且填滿整個按鈕 */
      border-radius: 50%;
      /* 讓圖片本身也是圓形 */
    }

    strong {
      font-weight: 700;
      /* 粗體 */
      font-size: 18px;
      /* 設定大小 */
    }
  </style>

  <!-- ========================= 個人資料區塊 Start ========================= -->
  <section class="contact-section pt-130">
    <div class="container">
      <div class="d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-xl-8">
          <div class="contact-form-wrapper p-5 bg-white shadow-lg rounded-3">
            <div class="row">
              <div class="col-xl-10 col-lg-8 mx-auto">
                <div class="section-title text-center mb-4">
                  <div class="header-section">
                    <div class="container">

                      <?php
                      session_start();
                      $servername = "127.0.0.1";
                      $username = "HCHJ";
                      $password = "xx435kKHq";
                      $dbname = "HCHJ";

                      $conn = new mysqli($servername, $username, $password, $dbname);
                      if ($conn->connect_error) {
                        die("連接失敗: " . $conn->connect_error);
                      }

                      if (isset($_SESSION['user'])) {
                        $userId = $_SESSION['user']['user'];
                      } else {
                        die("未登入，無法顯示圖片。");
                      }

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

                      <!-- 頭像區域 -->
                      <form enctype="multipart/form-data" action="/~HCHJ/Home/contact02-2.php" method="POST"
                        class="text-center">
                        <input type="file" id="file-input" name="image" accept=".jpg,.jpeg,.png" style="display: none;"
                          onchange="previewAndConfirm(event)">

                        <button type="button" id="button-image" onclick="document.getElementById('file-input').click();"
                          class="profile-pic-container">
                          <img src="data:image/jpeg;base64,<?= base64_encode($imageData) ?>" alt="button-image"
                            class="profile-pic">
                        </button>

                        <p class="text-muted mt-2">點擊圖片更換頭貼</p>
                        <input type="submit" value="上傳圖片" style="display:none;" id="submit-button">
                      </form>

                      <script>
                        function previewAndConfirm(event) {
                          var file = event.target.files[0];
                          if (file) {
                            var confirmChange = confirm("是否更換新頭貼？");

                            if (confirmChange) {
                              var reader = new FileReader();
                              reader.onload = function (e) {
                                document.querySelector('.profile-pic').src = e.target.result;
                              }
                              reader.readAsDataURL(file);

                              setTimeout(function () {
                                document.forms[0].submit();
                              }, 500);
                            } else {
                              document.getElementById('file-input').value = "";
                            }
                          }
                        }
                      </script>

                      <h3 class="mt-4 fw-bold text-dark"><?php echo $_SESSION['user']['name']; ?> 導師</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- 個人資料卡 -->
            <div class="bg-light p-4 rounded shadow-sm text-center">
              <p><strong>帳號名稱：</strong> <?php echo $_SESSION['user']['user']; ?></p></br>
              <p><strong>科系：</strong> <?php echo $_SESSION['user']['department']; ?></p></br>
              <p><strong>班級：</strong> <?php echo $_SESSION['user']['grade'], $_SESSION['user']['class']; ?></p></br>


              <!-- 班導資訊 -->
              <?php
              $link = mysqli_connect($servername, $username, $password, $dbname);
              if ($link) {
                mysqli_query($link, 'SET NAMES UTF8');
              } else {
                echo "無法連接資料庫：" . mysqli_connect_error();
                exit();
              }

              $userData = $_SESSION['user'];
              $grade = $userData['grade'];
              $class = $userData['class'];
              $currentUserId = $userData['id'];
              $permissions1 = explode(',', $userData['Permissions']);

              $sql = "SELECT * FROM `user` WHERE `grade` LIKE '%$grade%' AND `class` LIKE '%$class%' AND `id` != $currentUserId";
              $result = mysqli_query($link, $sql);

              if ($result) {
                $teachers = [];
                while ($row = mysqli_fetch_assoc($result)) {
                  $permissions2 = explode(',', $row['Permissions']);
                  if (in_array('2', $permissions2)) {
                    $teachers[] = $row['name'];
                  }
                }
              } else {
                echo "查詢失敗：" . mysqli_error($link);
              }
              
              $sql = "SELECT * FROM `testemail` WHERE `name`='$name'";
              $result = mysqli_query($link, $sql);
              if ($result) { 
            
                $studentemail = "";
                while ($row = mysqli_fetch_assoc($result)) {
                  
                  $studentemail = $row['email'];
                  echo "<p><strong>電子信箱：</strong><span>" . $studentemail . "</span></p>";
                }
              }

              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- ========================= 個人資料區塊 End ========================= -->

  <style>
    .profile-pic-container {
      border: none;
      background: none;
      cursor: pointer;
      display: inline-block;
      transition: transform 0.3s ease-in-out;
    }

    .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      border: 3px solid #ddd;
      object-fit: cover;
      box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .profile-pic-container:hover {
      transform: scale(1.05);
    }

    .contact-form-wrapper {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }
  </style>


  <!-- ========================= client-logo-section start ========================= -->
  
  <!-- ========================= client-logo-section end ========================= -->



  <!-- ========================= footer start ========================= -->
  <footer class="footer pt-100">
    <div class="container">
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-6">
          <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
            <a href="index-04.html" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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