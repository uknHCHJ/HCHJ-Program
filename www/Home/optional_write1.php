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
?>
<!doctype html>
<html class="no-js" lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>二技志願填選</title>
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
                                    <a class="page-scroll" href="index-01.php">首頁</a>
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
                                    <a class="nav-item dd-menu">備審資料</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="/~HCHJ/Home/recordforreview01-1.php">備審紀錄</a>
                                        </li>

                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/Contestblog-01.php">比賽資訊</a>
                                </li>
                                <li class="nav-item">
                                    <a class="page-scroll" href="/~HCHJ/Home/Contest-history(學生).php">競賽紀錄</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-item dd-menu">志願序</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="/~HCHJ/Home/optional_write1.php">選填志願</a>
                                        </li>
                                        <li class="nav-item"><a href="/~HCHJ/Home/optional_show1.php">查看志願序</a>
                                        </li>
                                    </ul>
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
                        <h2 class="text-white">二技志願選填</h2>
                        <div class="page-breadcrumb">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item" aria-current="page"><a href="index-04.php">首頁</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">二技志願選填</li>
                                </ol>
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
                                <?php if (!$isFifthYear): ?>
                                    <h1>此功能尚未開放</h1>
                                <?php else: ?>
                                    <h1>選擇你的志願</h1>
                                    <label for="schoolSelect">選擇學校:</label>
                                    <select id="schoolSelect" onchange="fetchDepartments()">
                                        <option value="">--請選擇學校--</option>
                                    </select>

                                    <label for="departmentSelect">選擇科系:</label>
                                    <select id="departmentSelect">
                                        <option value="">--請選擇科系--</option>
                                    </select>

                                    <!-- 新增兩個按鈕 -->
                                    <button onclick="add()">添加到清單</button>

                                    <h2>你的志願序(最多5個)</h2>
                                    <ul id="preferenceList"></ul>
                                    <button onclick="submit()">送出志願</button>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
            <script>
                const maxPreferences = 5;
                let preferences = [];

                document.addEventListener('DOMContentLoaded', () => {
                    fetchSchools();
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

                    if (preferences.length >= maxPreferences) {
                        alert('最多只能選擇5個志願');
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
                    li.textContent = `${order}. ${preference}`;

                    // 創建刪除按鈕，並將其放置在每個項目的末尾
                    const deleteBtn = document.createElement('button');
                    deleteBtn.classList.add('delete-btn');
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
                        li.textContent = `${preference.order}. ${preference.preference_rank}`;

                        // 創建刪除按鈕，並將其放置在每個項目的末尾
                        const deleteBtn = document.createElement('button');
                        deleteBtn.classList.add('delete-btn');
                        deleteBtn.onclick = () => deletePreference(index);  // 刪除該志願項目

                        li.appendChild(deleteBtn);  // 確保刪除按鈕在項目的末尾
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

                    fetch("addPreference.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({
                            preferences: preferences.map((pref, index) => ({
                                serial_number: index + 1,
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
                                window.location.href = "optional_show1.php";
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
                    <a href="index-01.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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