<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo "未登入";
    header("Location:/~HCHJ/index.php");
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

    <!-- 頁首 -->
    <header class="header navbar-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <nav class="navbar navbar-expand-lg">
                        <a class="navbar-brand" href="index-0.php">
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
                                        <a href="index-0.php">首頁</a>
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
                                </ul>                                    
                            </div> <!-- navbar collapse -->
                    </nav>
                </div>
            </div>
        </div>
    </header>

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
                                    <li class="breadcrumb-item" aria-current="page"><a href="index-0.php">首頁</a></li>
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
                <form id="upload-form" action="uploadAdduser.php" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label for="action-select">選擇新增方式：</label>
                        <select id="action-select" class="form-control" onchange="toggleUploadForm()">
                            <option value="upload">上傳 Excel 文件</option>
                            <option value="manual">手動新增成員</option>
                        </select>
                    </div>

                    <div id="upload-section">
                        <div class="form-group mb-3">
                            <label for="excel-file">選擇 Excel 文件：</label>
                            <input type="file" id="excel-file" name="excel_file" class="form-control" accept=".xls,.xlsx">
                        </div>
                        <div class="text-center mt-4">
                            <a href="download_excel.php" class="btn btn-success" download>下載範例 Excel 檔案</a>
                        </div>
                    </div>

                    <div id="manual-section" style="display: none;">

                        <div class="form-group mb-3">
                            <label for="department">選擇科系：</label>
                            <select id="department" name="department" class="form-select" >
                                <option value="護理科">護理科</option>
                                <option value="資訊管理科">資訊管理科</option>
                                <option value="企業管理科">企業管理科</option>
                                <option value="幼兒保育科">幼兒保育科</option>
                                <option value="視光科">視光科</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="user">帳號(學生學號)：</label>
                            <input type="text" id="user" name="user" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                        <label for="grade">選擇年級：</label>
                        <select id="grade" name="grade" class="form-select" >
                            <option value="1">一年級</option>
                            <option value="2">二年級</option>
                            <option value="3">三年級</option>
                            <option value="4">四年級</option>
                            <option value="5">五年級</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                            <label for="name">班級：</label>
                            <input type="text" id="class" name="class" class="form-control" required>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="name">名字：</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="permission">選擇權限：</label>
                            <select id="permissions" name="permissions" class="form-select">
                                <option value="1">學生</option>
                                <option value="2">班級導師</option>
                                <option value="3">一般老師</option>
                                <option value="4">最高行政人員</option>
                                <option value="0">管理員</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">送出</button>
                </form>
            </div>


        </div>
    </section>

    <!-- 引入JS -->
    <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>

    <script>
    function toggleUploadForm() {
        var select = document.getElementById('action-select'); // 確保在這裡定義 select
        console.log(select.value);  // 這裡可以安全地訪問 select.value

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

    // 頁面加載時預設顯示上傳部分
    document.addEventListener('DOMContentLoaded', function() {
        toggleUploadForm();
    });
</script>

</body>
</html>
