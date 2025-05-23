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

$query = sprintf("SELECT * FROM user WHERE user = '%d'", mysqli_real_escape_string($link, $userId));
$result = mysqli_query($link, $query);

if (!isset($_SESSION['user'])) {
    echo ("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}
?>

<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>備審素材區</title>
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

    <style>
        /* 設定容器和表單樣式 */
        .form-container {
            text-align: center;
            width: 100%;
            max-width: 500px;
            /* 設定最大寬度 */
            margin: 0 auto;
            padding: 20px;
        }

        /* 調整標籤樣式 */
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            font-size: 1.2em;
            /* 增加字型大小 */
            margin-top: 10px;
        }

        /* 設定 select、input 和 textarea 的樣式與大小 */
        select,
        input[type="text"],
        textarea,
        input[type="file"],
        input[type="date"] {
            width: 100%;
            max-width: 500px;
            /* 設定欄位最大寬度 */
            margin-top: 10px;
            padding: 8px;
            font-size: 1em;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        /* 設定按鈕樣式 */
        button {
            font-size: 1.2em;
            /* 增加按鈕字型大小 */
            padding: 10px 20px;
        }
    </style>
</head>
<?php
$servername = "127.0.0.1"; //伺服器ip或本地端localhost
$username = "HCHJ"; //登入帳號
$password = "xx435kKHq"; //密碼
$dbname = "HCHJ"; //資料表名稱


//建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

//確認連線成功或失敗
if ($conn->connect_error) {
    die("連線失敗" . $conn->connect_error);
}
?>

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
                            <a class="page-scroll" href="index-01.php" >首頁</a>
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
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_write1.php">選填志願(技優)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_write2.php">選填志願(申請入學)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_show1.php">查看志願序(技優)</a>
                                    </li>
                                    <li class="nav-item"><a href="/~HCHJ/Home/optional_show3-1.php">查看志願序(申請入學)</a>
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
                        <h2 class="text-white">備審素材區</h2>
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
    <div style="text-align: center; margin: auto;">
        <h1>備審素材區</h1>
        <form action="PortfolioCreat.php" method="post" enctype="multipart/form-data" id="uploadForm"
            onsubmit="return confirmUpload()" style="display: inline-block; text-align: center;">
            <input type="hidden" name="student_id"
                value="<?php echo htmlspecialchars($userId, ENT_QUOTES, 'UTF-8'); ?>">
            <!-- 將 organization 的 hidden input 移入 form 內 -->
            <input type="hidden" id="selectedOrganization" name="organization">

            <label for="category">選擇資料類型：</label>
            <select name="category" id="category" required>
                <option value="成績單">成績單</option>
                <option value="自傳">自傳</option>
                <option value="學歷證明">學歷證明</option>
                <option value="競賽證明">競賽證明</option>
                <option value="實習證明">實習證明</option>
                <option value="專業證照">專業證照</option>
                <option value="語言能力證明">語言能力證明</option>
                <option value="專題資料">專題資料</option>
                <option value="讀書計畫">讀書計畫</option>
                <option value="服務證明">服務證明</option>
                <option value="其他資料">其他資料</option>
            </select>

            <!-- 隱藏欄位，存選擇的值 -->
            <input type="hidden" name="selected_category" id="selected_category">

            <script>
                document.getElementById("category").addEventListener("change", function () {
                    document.getElementById("selected_category").value = this.value;
                });
            </script>


            <div id="sub_category_div" style="display: none;">
                <label for="sub_category">專業證照分類：</label>
                <select name="sub_category" id="sub_category"></select>
            </div>

            <div id="certificate_div" style="display: none;">
                <label for="certificate">選擇證照：</label>
                <select name="certificate" id="certificate"></select>
                <input type="hidden" name="certificate_name" id="certificate_name">
            </div>

            <label for="file">上傳檔案：(只能上傳 PNG, JPG, DOC, DOCX 檔案)</label>
            <input type="file" name="file" id="file" required>

            <!-- 新增輸入框，讓使用者自行修改檔案名稱 -->
            <label for="customFileName">自訂檔名(非必填)：</label>
            <input type="text" id="customFileName" name="customFileName" placeholder="輸入新的檔名">
            <!-- 添加間距 -->
            <br><br>

            <button type="submit" onclick="return confirm('確定要上傳此檔案嗎？')" style="background-color: blue; color: white; border: none; padding: 10px 20px; 
            font-size: 16px; border-radius: 5px; cursor: pointer;">
                上傳
            </button>
            <p>(注意!上傳檔案後會通知該班導查看)</p>
            <input type="hidden" name="force" id="forceField" value="1">
        </form>
    </div>

    <script>

        function confirmUpload() {
            return confirm("確定要上傳這份資料嗎？");
        }
        document.getElementById("uploadForm").addEventListener("submit", function (event) {
            const fileInput = document.getElementById("file");
            const fileNameInput = document.getElementById("customFileName");

            if (fileInput.files.length > 0) {
                let file = fileInput.files[0];
                let customFileName = fileNameInput.value.trim();

                if (customFileName) {
                    // 確保檔名有副檔名
                    let extension = file.name.split('.').pop();
                    if (!customFileName.includes(".")) {
                        customFileName += "." + extension;
                    }
                    fileNameInput.value = customFileName; // 確保自訂檔名被傳送到後端
                }
            }
        });
        document.addEventListener('DOMContentLoaded', () => {
            const categorySelect = document.getElementById("category");
            const subCategoryDiv = document.getElementById("sub_category_div");
            const certificateDiv = document.getElementById("certificate_div");
            const subCategorySelect = document.getElementById("sub_category");
            const certificateSelect = document.getElementById("certificate");
            const certificateNameInput = document.getElementById("certificate_name");

            // 定義可選擇的機構 (organization)
            const subCategories = ["ACM", "Adobe", "全球學習與測評發展中心(GLAD)", "Microsoft", "中華民國電腦教育發展協會(MOCC)", "勞動部勞動力發展署", "台灣醫學資訊協會", "美國教育測驗服務社(ETS)", "財團法人中華民國電腦技能基金會(TQC)", "財團法人語言訓練測驗中心"];

            // 監聽「類別」選擇變更事件
            categorySelect.addEventListener("change", () => {
                if (categorySelect.value === "專業證照") {
                    subCategoryDiv.style.display = "block";
                    subCategorySelect.innerHTML = "<option value=''>請選擇機構</option>";
                    certificateDiv.style.display = "none"; // 隱藏證照選擇區塊
                    certificateSelect.innerHTML = "<option value=''>請選擇證照</option>";

                    // 填充機構 (organization) 下拉選單
                    subCategories.forEach(org => {
                        let option = document.createElement("option");
                        option.value = org;
                        option.textContent = org;
                        subCategorySelect.appendChild(option);
                    });
                } else {
                    subCategoryDiv.style.display = "none";
                    certificateDiv.style.display = "none";
                }
            });

            // 將選擇的機構填入隱藏欄位 (請確保 hidden input 已放在 form 內)
            subCategorySelect.addEventListener("change", () => {
                document.getElementById("selectedOrganization").value = subCategorySelect.value;
            });

            // 監聽「機構」選擇變更事件，載入對應的證照
            subCategorySelect.addEventListener("change", () => {
                let selectedOrg = subCategorySelect.value;
                if (selectedOrg) {
                    certificateDiv.style.display = "block";
                    certificateSelect.innerHTML = "<option value=''>請選擇證照</option>";

                    // 透過 AJAX 請求從資料庫獲取該機構的證照清單
                    fetch(`GetCertifications.php?organization=${encodeURIComponent(selectedOrg)}`)
                        .then(response => response.json())
                        .then(certifications => {
                            certifications.forEach(cert => {
                                let option = document.createElement("option");
                                option.value = cert.id;
                                option.textContent = cert.name;
                                certificateSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error fetching certifications:", error));
                } else {
                    certificateDiv.style.display = "none";
                }
            });

            // 監聽「證照」選擇變更事件，填入隱藏欄位
            certificateSelect.addEventListener("change", () => {
                certificateNameInput.value = certificateSelect.options[certificateSelect.selectedIndex].text;
            });
        });
    </script>

    <div class="portfolio-section pt-130">
        <div id="container" class="container">
            <div class="row">
                <div class="col-12">
                    <div class="portfolio-btn-wrapper">
                        <button type="button" class="portfolio-btn active" data-filter="*">全部</button>
                        <button type="button" class="portfolio-btn" data-filter=".transcripts">成績單</button>
                        <button type="button" class="portfolio-btn" data-filter=".autobiography">自傳</button>
                        <button type="button" class="portfolio-btn" data-filter=".certificates">學歷證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".competitions">競賽證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".internships">實習證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".licenses">專業證照</button>
                        <button type="button" class="portfolio-btn" data-filter=".language-skills">語言能力證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".Topics">專題資料</button>
                        <button type="button" class="portfolio-btn" data-filter=".reading-plan">讀書計畫</button>
                        <button type="button" class="portfolio-btn" data-filter=".Proof-of-service">服務證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".other-information">其他資料</button>
                    </div>

                    <div class="row grid">
                        <?php
                        $servername = "127.0.0.1";
                        $username = "HCHJ";
                        $password = "xx435kKHq";
                        $dbname = "HCHJ";

                        $conn = new mysqli($servername, $username, $password, $dbname);
                        if ($conn->connect_error) {
                            die("連線失敗：" . $conn->connect_error);
                        }

                        $sql = "SELECT * FROM portfolio WHERE student_id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $userId);
                        $stmt->execute();
                        $result = $stmt->get_result();

                        $category_map = [
                            "成績單" => "transcripts",
                            "自傳" => "autobiography",
                            "學歷證明" => "certificates",
                            "競賽證明" => "competitions",
                            "實習證明" => "internships",
                            "專業證照" => "licenses",
                            "語言能力證明" => "language-skills",
                            "專題資料" => "Topics",
                            "讀書計畫" => "reading-plan",
                            "服務證明" => "Proof-of-service",
                            "其他資料" => "other-information"
                        ];

                        $category_data_count = array_fill_keys(array_keys($category_map), 0);
                        $data_exists = false;

                        // 讀取資料並計算各類別數量
                        while ($row = $result->fetch_assoc()) {
                            $category_name = trim($row["category"]);
                            $category_class = $category_map[$category_name] ?? "unknown";

                            if (isset($category_map[$category_name])) {
                                $category_data_count[$category_name]++;
                            }

                            echo "<div class='col-lg-4 col-md-6 portfolio-item {$category_class}'>
                                <div class='portfolio-content'>
                                    <h3>{$row['category']}</h3>";

                            if ($row["category"] === "專業證照") {
                                echo "<p><strong>機構：</strong> " . htmlspecialchars($row["organization"], ENT_QUOTES, 'UTF-8') . "</p>";
                                echo "<p><strong>證照名稱：</strong> " . htmlspecialchars($row["certificate_name"], ENT_QUOTES, 'UTF-8') . "</p>";
                            }

                            echo "<p><a href='PortfolioDownload.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>{$row['file_name']}</a></p>
                                <p>上傳時間：{$row['upload_time']}</p>
                                <form action='PortfolioDelete.php' method='post'>
                                    <input type='hidden' name='id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                    <button type='submit' onclick='return confirm(\"確定要刪除這筆資料嗎？\")' 
                                        style='background-color: red; color: white; border: none; padding: 12px 24px; font-size: 16px; border-radius: 8px; cursor: pointer; margin-top: 5px; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.2);'>
                                        刪除
                                    </button>
                                </form>
                            </div>
                        </div>";

                            $data_exists = true;
                        }

                        $show_no_data_message = true; // 確保「尚無任何資料」只顯示一次
                        
                        // 檢查是否有資料，如果沒有則顯示「無資料」訊息
                        if (!$data_exists) {
                            echo "<div class='col-12'>
                                <p style='text-align: center; font-size: 18px; color: gray;'>尚無任何資料</p>
                            </div>";
                        } else {
                            foreach ($category_map as $category_name => $category_class) {
                                if ($category_data_count[$category_name] === 0) {
                                    echo "<div class='col-12 portfolio-item {$category_class}' style='display: none;'>
                                        <p style='text-align: center; font-size: 18px; color: gray;'>無資料</p>
                                    </div>";
                                }
                            }
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const buttons = document.querySelectorAll(".portfolio-btn");
            const items = document.querySelectorAll(".portfolio-item");

            buttons.forEach(button => {
                button.addEventListener("click", function () {
                    // 移除所有按钮的 active 类
                    buttons.forEach(btn => btn.classList.remove("active"));
                    this.classList.add("active");

                    const filter = this.getAttribute("data-filter");

                    items.forEach(item => {
                        if (filter === "*" || item.classList.contains(filter.substring(1))) {
                            item.style.display = "block"; // 顯示符合條件的項目
                        } else {
                            item.style.display = "none"; // 隱藏不符合的項目
                        }
                    });
                });
            });
        });

        function confirmUpload() {
            const studentId = document.querySelector('input[name="student_id"]').value;
            const category = document.getElementById("category").value;
            const fileInput = document.getElementById("file_name");
            const file = fileInput.files[0];

            if (!file) {
                alert('請選擇一個檔案來上傳');
                return false;
            }

            const fileName = file.name;

            // 透過 AJAX 檢查是否已經上傳過相同檔名的檔案
            return fetch('CheckDuplicate.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ student_id: studentId, category: category, file_name: fileName })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('您已經上傳過相同類別的相同檔案名稱，請選擇不同的檔案或變更檔名。');
                        return false;
                    }
                    return confirm(`您確定要上傳檔案：${file.name}？`);
                })
                .catch(error => {
                    console.error('檢查重複檔案時發生錯誤:', error);
                    return false;
                });
        }

    </script>

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
                        <a href="index-01.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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