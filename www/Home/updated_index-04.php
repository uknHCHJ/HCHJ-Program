<?php 
session_start();
// 資料庫連線
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = mysqli_connect($servername, $username, $password, $dbname);

// 確認連線
if (mysqli_connect_errno()) {
    die("連線失敗: " . mysqli_connect_error());
}

$userData = $_SESSION['user']; 

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username = $userData['name'];
?>
<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>升學競賽全方位資源網</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
    
    <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
    <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
    <link rel="stylesheet" href="assets/css/animate.css">
    <link rel="stylesheet" href="assets/css/tiny-slider.css">
    <link rel="stylesheet" href="assets/css/glightbox.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    
    <style>
        /* 左側邊欄的樣式 */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100%;
            background-color: #f8f9fa;
            padding-top: 20px;
            box-shadow: 2px 0px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease; /* 加入平滑過渡效果 */
            z-index: 1; /* 預設層級 */
        }
        .sidebar .sub-menu {
            display: none;  /* 預設隱藏 */
            opacity: 0;     /* 預設透明 */
            transition: opacity 0.3s ease; /* 平滑過渡 */
            position: relative;
            z-index: -1;    /* 確保子選單初始層級較低 */
        }
        .sidebar .nav-item:hover .sub-menu {
            display: block;   /* 顯示子選單 */
            opacity: 1;       /* 顯示不透明 */
            z-index: 999;     /* 確保子選單在最上層 */
        }
        .sidebar:hover {
            z-index: 9999; /* 滑鼠懸停時顯示在最上層 */
        }

        .sidebar .nav-item {
            margin: 10px 0;
        }

        .sidebar .nav-link {
            font-size: 16px;
            color: #333;
            padding: 10px 15px;
            text-decoration: none;
        }

        .sidebar .nav-item:hover {
            position: relative;
            z-index: 999; /* 當滑鼠懸停在選單項目上時，確保其在上層 */
        }

        /* 主內容區域 */
        .main-content {
            position: relative;
            margin-left: 260px;  /* 確保有足夠的距離 */
            padding: 20px;
        }

        /* 優化頁面中的圖片 */
        .hero-img img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

    <!-- 側邊欄 -->
    <div class="sidebar">
        <nav class="navbar navbar-expand-lg">
            
            <ul id="nav" class="navbar-nav flex-column">
            <a class="navbar-brand" href="index-04.php">
                <img src="schoolimages/uknlogo.png" alt="Logo" style="width: 100px;">
            </a>
                <li class="nav-item"><a href="index-04.php" class="nav-link">首頁</a></li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">個人資料</a>
                    <ul class="sub-menu">
                        <li><a href="contact-04.php">查看個人資料</a></li>
                        <li><a href="../changepassword-01.html">修改密碼</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">班級管理</a>
                    <ul class="sub-menu">
                        <li><a href="Contest-history1.php">查看學生備審資料</a></li>
                        <li><a href="order1.php">查看志願序</a></li>
                        <li><a href="Contest-history1.php">查看競賽歷程</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">比賽資訊</a>
                    <ul class="sub-menu">
                        <li><a href="/~HCHJ/Home/blog-1(競賽).html">查看</a></li>
                        <li><a href="/~HCHJ/Home/blog-1(競賽).html">新增</a></li>
                        <li><a href="/~HCHJ/Home/blog-1(競賽).html">編輯</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">目前登入使用者：<?php echo $userId; ?></a>
                </li>
                <li class="nav-item">
                    <a href="/~HCHJ/Permission.php" class="nav-link">切換使用者</a>
                </li>
                <li class="nav-item">
                    <a class="page-scroll" href="../logout.php" >登出</a>
                </li>
            </ul>
        </nav>
    </div>

    <!-- 主內容區域 -->
    <div class="main-content">
        <!-- 這裡放入原本的頁面內容，像是歡迎訊息及圖片等 -->
        <section id="home" class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-xl-5 col-lg-6">
                        <div class="hero-content-wrapper">
                            <h2 class="mb-25 wow fadeInDown" data-wow-delay=".2s">您好，<?php echo $username; ?></h2>
                            <h1 class="mb-25 wow fadeInDown" data-wow-delay=".2s">歡迎使用本系統</h1>
                            <script>
                                function submitLogout() {
                                    document.getElementById('logoutForm').submit();
                                }
                            </script>
                            <a href="javascript:void(0)" class="theme-btn" onclick="submitLogout()">登出</a>
                            <form id="logoutForm" action="../logout.php" method="POST" style="display:none;">
                            </form>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-6">
                        <img src="schoolimages/imlogo.png" alt="" class="wow fadeInRight" data-wow-delay=".5s">
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
    <script src="assets/js/main.js"></script>

</body>
</html>
