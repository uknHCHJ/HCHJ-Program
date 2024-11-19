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
<html lang="zh-Hant">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>新增學校科系</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">

    <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
        <!-- Place favicon.ico in the root directory -->

		<!-- ========================= CSS here ========================= -->
		<link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
		<link rel="stylesheet" href="assets/css/animate.css">
		<link rel="stylesheet" href="assets/css/tiny-slider.css">
		<link rel="stylesheet" href="assets/css/glightbox.min.css">
		<link rel="stylesheet" href="assets/css/main.css">
    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<?php
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

$school_id = $_GET['school_id'];

// 查詢科系資料
$sql = "SELECT department_name FROM department";
$result = $conn->query($sql);

// 查詢該校已選擇的科系
$existingDepartments = [];
$checkSql = "SELECT department_name FROM Department WHERE school_id = ?";
$checkStmt = $conn->prepare($checkSql);
$checkStmt->bind_param("s", $school_id);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

while ($row = $checkResult->fetch_assoc()) {
    $existingDepartments[] = $row['department_name'];
}
?>

    <!-- Header 部分省略，保持不變 -->

    <!-- ========================= page-banner-section start ========================= -->
    <section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="banner-content">
                        <h2 class="text-white">二技學校</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index-03.php">首頁</a></li>
                                    <li class="breadcrumb-item active">二技校園網介紹</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ========================= page-banner-section end ========================= -->
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
                                            <li class="nav-item"><a href="../changepassword-01.html">修改密碼</a></li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a class="page-scroll dd-menu" href="javascript:void(0)">班級管理</a>
                                        <ul class="sub-menu">
                                            <li class="nav-item"><a href="Contest-history1.php">查看學生備審資料</a></li>
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
        <section id="service" class="service-section pt-20 pb-10"> 
    <div style="text-align:center;width:100%;height:50px;">
        <div style="width:70%;height:20px;margin:0 auto;">
            <span class="wow fadeInDown" data-wow-delay=".2s">
                <h2>新增科系</h2>
            </span>
            <br><br>
            <form action="AddDepartments2-04.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="school_id" value="<?php echo htmlspecialchars($school_id); ?>">
                <label for="numChoices">科系數量：</label>
                <input type="number" id="numChoices" name="numChoices" min="1" max="10" required><br>
                <div id="selectContainer"></div><br>
                <button class="btn btn-success" type="submit" onclick="return confirm('確定要新增這些科系嗎？')">送出</button>
                <br><br>
                <a href="SchoolDepartment1-04.php?school_id=<?= $school_id ?>" class="btn btn-secondary">返回上一頁</a>

        </div>
    </div>
</section>

<script>
    // 監聽 "numChoices" 的輸入事件，當用戶更改科系數量時觸發
    document.getElementById('numChoices').addEventListener('input', function () {
        var numChoices = parseInt(this.value); // 取得用戶輸入的科系數量，並轉換為整數
        if (numChoices > 10) { // 如果科系數量超過 10，顯示警告並限制數量
            alert("最多只能新增 10 筆科系！"); // 提示用戶
            this.value = 10; // 將輸入值設為 10
            numChoices = 10; // 更新 numChoices 變量的值
        }
        var selectContainer = document.getElementById('selectContainer'); // 取得下拉選單的容器
        selectContainer.innerHTML = ''; // 清空容器中的所有內容

        // 根據用戶輸入的數量，動態生成下拉選單
        for (var i = 1; i <= numChoices; i++) {
            var label = document.createElement('label'); // 創建標籤元素
            label.innerHTML = ' 科系 ' + i; // 設置標籤的文字

            var select = document.createElement('select'); // 創建下拉選單
            select.name = 'choice' + i; // 設定下拉選單的 name 屬性
            select.className = "form-select mb-3"; // 設置下拉選單的樣式

            <?php
            // 從資料庫抓取科系選項
            if ($result->num_rows > 0) {
                echo 'var options = `<option value="">請選擇科系</option>`;'; // 預設選項
                while($row = $result->fetch_assoc()) {
                    $departmentName = htmlspecialchars($row["department_name"]);
                    // 檢查科系是否已存在，並設置相應的樣式
                    $disabled = in_array($departmentName, $existingDepartments) ? 'style="background-color: lightgray; color: darkgray;" disabled' : '';
                    echo 'options += `<option value="' . $departmentName . '" ' . $disabled . '>' . $departmentName . '</option>`;';
                }
            } else {
                echo 'var options = `<option value="">無科系資料</option>`;'; // 無科系資料的選項
            }
            ?>

            select.innerHTML = options; // 將生成的選項加入到下拉選單中
            selectContainer.appendChild(label); // 將標籤加入到容器中
            selectContainer.appendChild(select); // 將下拉選單加入到容器中
        }
    });
</script>

<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br><br>
<br>
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