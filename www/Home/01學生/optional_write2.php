<?php
session_start();
$userData = $_SESSION['user'];
$userId = $userData['user'] ?? null; // 檢查 session 是否有效
$username = $userData['name'] ?? null;
$grade = $userData['grade']; // 老師的年級
$class = $userData['class']; // 老師的班級

if (!isset($_SESSION['user'])) {
    echo ("<script>
                    alert('請先登入！！');
                    window.location.href = '/~HCHJ/index.html'; 
                  </script>");
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user']; // 取得用戶ID
$grade = $userData['grade']; // 取得年級

// 判斷是否為五年級
$isFifthYear = ($grade == 5);  // 如果年級是 5，則表示是五年級

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");

if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

mysqli_query($link, 'SET NAMES UTF8');

if (!isset($userId)) {
    die("userId 未定義");
}

$sql1 = "SELECT set_time.user,apply_type FROM set_time
left join user on set_time.user = user.user
where user.grade =$grade 
and user.class='$class'
and user.Permissions like '%2%4%'";
$res1 = mysqli_query($link, $sql1);

if (!$res1) {
    die("SQL 錯誤: " . mysqli_error($link));
}

$row1 = mysqli_fetch_assoc($res1);
$skill=false;
if ($row1 && $row1['apply_type'] === '申請入學') {
    // echo "符合 apply_type";
    $skill=true;
} else {
    // echo "不符合 apply_type";
    $skill=false;
}

?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>選填志願</title>
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
                        <h2 class="text-white">二技志願選填(申請入學)</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <head>
        <!-- ========================= CSS here ========================= -->
        <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
        <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
        <link rel="stylesheet" href="assets/css/animate.css">
        <link rel="stylesheet" href="assets/css/glightbox.min.css">
        <link rel="stylesheet" href="assets/css/tiny-slider.css">
        <link rel="stylesheet" href="assets/css/main.css">

        <style>
            body {
                font-family: Arial, sans-serif;
            }

            select,
            button {
                margin: 10px 0;
                padding: 5px;
            }
        </style>
    </head>

    <body>

        <!-- ========================= header start ========================= -->
        <header class="header navbar-area">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <nav class="navbar navbar-expand-lg">
                            <a class="navbar-brand" href="index-01.php">
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse"
                                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                aria-expanded="false" aria-label="Toggle navigation">
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                                <span class="toggler-icon"></span>
                            </button>
                        </nav> <!-- navbar -->
                    </div>
                </div> <!-- row -->
            </div> <!-- container -->
        </header>

        <head>
            <meta charset="utf-8">
            <meta http-equiv="x-ua-compatible" content="ie=edge">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <title>二技志願填選</title>
            <meta name="description" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

            <!-- ========================= CSS here ========================= -->
            <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
            <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
            <link rel="stylesheet" href="assets/css/animate.css">
            <link rel="stylesheet" href="assets/css/tiny-slider.css">
            <link rel="stylesheet" href="assets/css/glightbox.min.css">
            <link rel="stylesheet" href="assets/css/main.css">

            <style>
                body {
                    font-family: Arial, sans-serif;
                }

                select,
                button {
                    margin: 10px 0;
                    padding: 5px;
                }

                select {
                    appearance: none;
                    padding: 10px;
                    width: 100%;
                    max-width: 100%;
                    font-size: 16px;
                    border: 2px solid #ccc;
                    border-radius: 8px;
                    background-color: #fff;
                    color: #333;
                    margin-bottom: 15px;
                    transition: border-color 0.3s ease;
                }

                select:focus {
                    border-color: #5cb85c;
                    outline: none;
                }

                select {
                    appearance: none;
                    padding: 8px;
                    width: 100%;
                    font-size: 1em;
                    border: 1px solid #ccc;
                    border-radius: 8px;
                    background-color: #fff;
                    color: #333;
                    margin-bottom: 10px;
                    transition: border-color 0.3s ease;
                }

                select:focus {
                    border-color: #5cb85c;
                    outline: none;
                }

                /* 改善送出按鈕樣式 */
                button {
                    background-color: #5cb85c;
                    color: white;
                    font-size: 1em;
                    padding: 10px 15px;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }

                button:hover {
                    background-color: #4cae4c;
                }

                /* 調整結果顯示區塊的樣式 */
                #preferenceList {
                    background-color: #f9f9f9;
                    /* 背景色淡灰 */
                    padding: 15px;
                    /* 內距 */
                    border: 1px solid #ddd;
                    /* 邊框 */
                    border-radius: 8px;
                    /* 圓角 */
                    margin-top: 10px;
                    max-height: 200px;
                    /* 限制最大高度 */
                    overflow-y: auto;
                    /* 當內容超過時滾動 */
                }

                /* 每個志願項目樣式 */
                #preferenceList li {
                    background-color: #e7f3fe;
                    /* 輕微藍色底 */
                    margin: 5px 0;
                    padding: 10px;
                    border-left: 5px solid #2196F3;
                    /* 左側藍條 */
                    color: #333;
                }

                /* 垃圾桶圖示按鈕的樣式 */
                .delete-btn {
                    background: url('https://img.icons8.com/ios/452/trash.png') no-repeat center center;
                    background-size: 20px;
                    width: 24px;
                    height: 24px;
                    border: none;
                    cursor: pointer;
                    margin-left: 10px;
                }

                .delete-btn:hover {
                    background-color: #f44336;
                }
            </style>
            <style>
                /* 基本樣式 */
                #preferenceList {
                    padding: 15px;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    background-color: #f9f9f9;
                    list-style-type: none;
                    margin-top: 15px;
                    max-height: 300px;
                    overflow-y: auto;
                }

                #preferenceList li {
                    background-color: #e7f3fe;
                    margin: 5px 0;
                    padding: 10px;
                    border-left: 5px solid #2196F3;
                    display: flex;
                    justify-content: space-between;
                    /* 讓刪除按鈕置於右邊 */
                    align-items: center;
                    color: #333;
                    border-radius: 5px;
                }

                /* 刪除按鈕樣式 */
                .delete-btn {
                    color: black;
                    border: none;
                    border-radius: 4px;
                    padding: 5px 10px;
                    font-size: 14px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                }
            </style>
        </head>

        <body>
            <!-- ========================= header start ========================= -->
            <header class="header navbar-area">
                <!-- header code ... -->
            </header>
            <!-- ========================= header end ========================= -->

            <!-- ========================= form section start ========================= -->
            <section class="form-section pt-75 pb-75">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <div class="container">

                                <!-- 檢查是否為五年級 -->
                                <?php if (!$isFifthYear || !$skill): ?>
                                    <h1 class="text-center text-danger">此功能尚未開放</h1>
                                <?php else: ?>
                                    <h1 id="formTitle" class="text-center text-primary">選擇你的志願</h1>
                                    <div id="timeStatus" class="text-center mb-4"></div> <!-- 顯示時間狀態 -->

                                    <div id="formContent" class="form-content" style="display: none;">
                                        <div class="mb-3">
                                            <label for="schoolSelect" class="form-label">選擇學校:</label>
                                            <select id="schoolSelect" class="form-select" onchange="fetchDepartments()">
                                                <option value="">--請選擇學校--</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="departmentSelect" class="form-label">選擇科系:</label>
                                            <select id="departmentSelect" class="form-select">
                                                <option value="">--請選擇科系--</option>
                                            </select>
                                        </div>

                                        
                                        <div class="mb-3">
                                            <button onclick="add()" class="btn btn-success w-100">添加到清單</button>
                                        </div>

                                        <h2>你的志願序</h2>
                                        <ul id="preferenceList" class="list-group mb-3" style="max-height: 500px; overflow-y: auto;"></ul>


                                        <button onclick="submit()" class="btn btn-primary w-100">送出志願</button>
                                    </div>

                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                const maxPreferences = 5;
                let preferences = [];

                document.addEventListener('DOMContentLoaded', () => {
                    fetch('get_time2-02.php')  // 呼叫後端 API 來取得時間設定
                        .then(response => response.json())
                        .then(data => {
                            const currentTime = new Date();
                            const openTime = new Date(data.open_time);
                            const closeTime = new Date(data.close_time);
                            const timeStatus = document.getElementById('timeStatus'); //時間狀態
                            const formContent = document.getElementById('formContent'); //志願序區塊
                            const formTitle = document.getElementById('formTitle'); // 時間

                            // 檢查是否有設定時間
                            if (!data.open_time || !data.close_time) {
                                timeStatus.innerHTML = '填選志願功能尚未開放';
                                formTitle.style.display = 'none'; // 隱藏標題
                            } else if (currentTime < openTime) {
                                timeStatus.innerHTML = '填選志願功能尚未開放';
                                formTitle.style.display = 'none'; // 隱藏標題
                            } else if (currentTime > closeTime) {
                                timeStatus.innerHTML = '填選志願功能尚未開放';
                                formTitle.style.display = 'none'; // 隱藏標題
                            } else {
                                timeStatus.innerHTML = '';
                                formContent.style.display = 'block';  // 顯示志願填寫表單
                                fetchSchools();
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching time:', error);
                            document.getElementById('timeStatus').innerHTML = '無法取得填寫時間設定';
                        });
                });

                function fetchSchools() {
                    fetch('getSchools.php')
                        .then(response => response.json())
                        .then(data => {
                            const schoolSelect = document.getElementById('schoolSelect');
                            data.forEach(school => {
                                const option = document.createElement('option');
                                option.value = school.school_id;
                                option.textContent = school.school_name;
                                schoolSelect.appendChild(option);
                            });
                        });
                }

                function fetchDepartments() {
                    const schoolId = document.getElementById('schoolSelect').value;
                    if (!schoolId) return;

                    fetch(`getDepartments.php?school_id=${schoolId}`)
                        .then(response => response.json())
                        .then(data => {
                            const departmentSelect = document.getElementById('departmentSelect');
                            departmentSelect.innerHTML = '<option value="">--請選擇科系--</option>';
                            data.forEach(dept => {
                                const option = document.createElement('option');
                                option.value = dept.department_id;
                                option.textContent = dept.department_name;
                                departmentSelect.appendChild(option);
                            });
                        });
                }

                function add() {
                    const schoolSelect = document.getElementById('schoolSelect');
                    const departmentSelect = document.getElementById('departmentSelect');

                    // 確保選擇了學校和科系
                    if (!schoolSelect.value || !departmentSelect.value) {
                        alert('請先選擇學校和科系');
                        return;
                    }

                    const preference = `${schoolSelect.options[schoolSelect.selectedIndex].text} - ${departmentSelect.options[departmentSelect.selectedIndex].text}`;

                    // 檢查志願是否已經被選過
                    if (preferences.some(p => p.preference_rank === preference)) {
                        alert('此志願已經選擇過，請選擇其他的志願');
                        return;
                    }

                    // 添加序號和選擇的志願資訊
                    const order = preferences.length + 1;
                    preferences.push({
                        order: order,
                        Secondskill_id: schoolSelect.value,
                        school_name: schoolSelect.options[schoolSelect.selectedIndex].text,
                        departmentId: departmentSelect.value,
                        department_name: departmentSelect.options[departmentSelect.selectedIndex].text,
                        preference_rank: preference
                    });

                    // 顯示志願清單並添加序號
                    const preferenceList = document.getElementById('preferenceList');
                    const li = document.createElement('li');
                    li.classList.add('list-group-item');
                    li.textContent = `${order}. ${preference}`;

                    // 創建刪除按鈕，並將其放置在每個項目的末尾
                    const deleteBtn = document.createElement('button');
                    deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ms-2');
                    deleteBtn.textContent = '刪除';
                    deleteBtn.onclick = () => deletePreference(order - 1);  // 刪除該志願項目

                    li.appendChild(deleteBtn);  // 確保刪除按鈕在項目的末尾
                    preferenceList.appendChild(li);

                    // 重置學校和科系下拉選單
                    schoolSelect.value = '';
                    departmentSelect.innerHTML = '<option value="">--請選擇科系--</option>';
                }

                // 刪除志願項目
                function deletePreference(index) {
                    preferences.splice(index, 1);
                    // 重整序號
                    preferences.forEach((preference, idx) => {
                        preference.order = idx + 1;
                    });
                    renderPreferences(); // 更新顯示的列表
                }

                // 渲染志願列表
                function renderPreferences() {
                    const preferenceList = document.getElementById('preferenceList');
                    preferenceList.innerHTML = '';

                    preferences.forEach((preference, index) => {
                        const li = document.createElement('li');
                        li.classList.add('list-group-item');
                        li.textContent = `${preference.order}. ${preference.preference_rank}`;

                        // 創建刪除按鈕，並將其放置在每個項目的末尾
                        const deleteBtn = document.createElement('button');
                        deleteBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'ms-2');
                        deleteBtn.textContent = '刪除';
                        deleteBtn.onclick = () => deletePreference(index); 

                        li.appendChild(deleteBtn);
                        preferenceList.appendChild(li);
                    });
                }

                function submit() {
                    if (preferences.length === 0) {
                        alert("請先添加至少一個志願");
                        return;
                    }

                    // 加入確認視窗
                    if (!confirm("確定要送出志願嗎？")) {
                        return;
                    }

                    fetch("addApply.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            preferences: preferences.map((pref, index) => ({
                                preference_rank: index + 1,  // 這裡把 serial_number 改為 preference_rank
                                Secondskill_id: pref.Secondskill_id,
                                department_id: pref.departmentId,
                                school_name: pref.school_name,
                                department_name: pref.department_name
                            })),
                        }),
                    })
                        .then((response) => {
                            if (!response.ok) {
                                throw new Error(`伺服器回應錯誤，狀態碼: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then((data) => {
                            if (data.success) {
                                alert("志願序送出成功");
                                window.location.href = "optional_show3-1.php";
                            } else {
                                alert("儲存失敗: " + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error("Error:", error);
                            alert("發生錯誤: " + error.message);
                        });

                }
            </script>

        </body>


        <!-- ========================= footer end ========================= -->
        <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
        <link rel="stylesheet" href="assets/css/tiny-slider.css">
        <script src="assets/js/contact-form.js"></script>
        <script src="assets/js/count-up.min.js"></script>
        <script src="assets/js/isotope.min.js"></script>
        <script src="assets/js/glightbox.min.js"></script>
        <script src="assets/js/wow.min.js"></script>
        <script src="assets/js/imagesloaded.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>

</html>

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