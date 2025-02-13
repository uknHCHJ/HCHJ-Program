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

$userData = $_SESSION['user'];
$userId = $userData['user']; 
$username = $userData['name'];
?>
<!doctype html>
<html class="no-js" lang="zh-TW">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>匯出檔案</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
  <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
  <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/main.css">
</head>
<body>

<header class="header navbar-area">
  <div class="container">
    <nav class="navbar navbar-expand-lg">
      <a class="navbar-brand" href="index-00.php">
        <img src="schoolimages/uknlogo.png" alt="Logo">
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse"
        data-target="#navbarSupportedContent">
        <span class="toggler-icon"></span>
        <span class="toggler-icon"></span>
        <span class="toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
        <ul id="nav" class="navbar-nav ml-auto">
          <li class="nav-item"><a href="index-00.php">首頁</a></li>
          <li class="nav-item"><a href="contact-00.php">個人資料</a></li>
          <li class="nav-item"><a href="../changepassword.html">修改密碼</a></li>  
          <li class="nav-item"><a href="Adduser.php">新增人員</a></li>        
          <li class="nav-item"><a href="Access-Control1.php">權限管理</a></li>
          <li class="nav-item"><a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a></li>
          <li class="nav-item"><a class="page-scroll" href="/~HCHJ/Permission.php">切換使用者</a></li>
          <li class="nav-item"><a class="page-scroll" href="../logout.php">登出</a></li>                          
        </ul>                                    
      </div>
    </nav>
  </div>
</header>

<section class="page-banner-section pt-75 pb-75 img-bg" style="background-image: url('assets/img/bg/common-bg.svg')">
  <div class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="banner-content">
          <h2 class="text-white">匯出檔案</h2>
        </div>
      </div>
    </div>
  </div>
</section>

<section id="service" class="service-section pt-130 pb-100">
  <div class="container">
    <div class="row">
      <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
        <div class="section-title text-center mb-55">
          <span class="wow fadeInDown" data-wow-delay=".2s">選擇需要匯出的檔案</span>
        </div>
      </div>
    </div>

    <div class="table-container">
      <form id="export-form" action="export-file2.php" method="POST">
        <div class="form-group mb-3">
          <label for="user">匯出帳號：</label>
          <input type="text" id="user" name="user" class="form-control" value="<?php echo $userId; ?>" readonly>
        </div>

        <div class="form-group mb-3">
          <label class="checkbox-label">
            選擇匯出資料 <span style="color: red;">（可拖曳排序）</span>：
          </label>
          <ul id="sortable-list" class="list-group">
            <li class="list-group-item" data-value="transcript">
              <input type="checkbox" name="options[]" value="transcript"> 成績單
            </li>
            <li class="list-group-item" data-value="autobiography">
  <input type="checkbox" id="autobiography-checkbox" name="options[]" value="autobiography"> 自傳
  <div id="autobiography-container" style="display: none; margin-top: 10px;">
    <div id="autobiography-options" class="autobiography-options"></div>
    <div id="autobiography-output" style="margin-top: 10px; font-weight: bold;"></div>
  </div>
</li>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    var autoCheckbox = document.getElementById('autobiography-checkbox');
    var autoContainer = document.getElementById('autobiography-container');
    var autoOptionsDiv = document.getElementById('autobiography-options');
    var autoOutputDiv = document.getElementById('autobiography-output');

    autoCheckbox.addEventListener('change', function() {
      if (this.checked) {
        autoContainer.style.display = 'block';
        fetch('get-autobiography.php?type=autobiography')
          .then(response => response.json())
          .then(data => {
            autoOptionsDiv.innerHTML = '';
            data.forEach(file => {
              var label = document.createElement('label');
              var optCheckbox = document.createElement('input');
              optCheckbox.type = 'checkbox';
              optCheckbox.name = 'autobiography_files[]';
              optCheckbox.value = file.filename;
              optCheckbox.addEventListener('change', updateAutoOutput);
              label.appendChild(optCheckbox);
              label.appendChild(document.createTextNode(' ' + file.filename));
              autoOptionsDiv.appendChild(label);
            });
          })
          .catch(error => console.error('取得自傳清單錯誤:', error));
      } else {
        autoContainer.style.display = 'none';
        autoOptionsDiv.innerHTML = '';
        autoOutputDiv.innerText = '';
      }
    });

    function updateAutoOutput() {
      var selectedAutos = [];
      autoOptionsDiv.querySelectorAll('input[type="checkbox"]:checked').forEach(function(chk) {
        selectedAutos.push(chk.value);
      });
      autoOutputDiv.innerText = selectedAutos.length > 0 ? '選取的自傳：' + selectedAutos.join(', ') : '';
    }
  });
</script>
            <li class="list-group-item" data-value="diploma">
              <input type="checkbox" name="options[]" value="diploma"> 學歷證明
            </li>
            <li class="list-group-item" data-value="competition">
              <input type="checkbox" name="options[]" value="competition"> 競賽證明
            </li>
            <li class="list-group-item" data-value="internship">
              <input type="checkbox" name="options[]" value="internship"> 實習證明
            </li>
            <li class="list-group-item" data-value="certifications">
              <input type="checkbox" id="certifications-checkbox" name="options[]" value="certifications"> 相關證照
              <!-- 顯示證照選項區塊 -->
              <div id="certifications-container" style="display: none; margin-top: 10px;">
                <div id="certifications-options" class="certifications-options"></div>
                <!-- 輸出已選取證照資料 -->
                <div id="certifications-output" style="margin-top: 10px; font-weight: bold;"></div>
              </div>
            </li>
            <script>
              document.addEventListener('DOMContentLoaded', function() {
              var certCheckbox = document.getElementById('certifications-checkbox');
              var certContainer = document.getElementById('certifications-container');
              var certOptionsDiv = document.getElementById('certifications-options');
              var certOutputDiv = document.getElementById('certifications-output');

  certCheckbox.addEventListener('change', function() {
    if (this.checked) {
      certContainer.style.display = 'block';
      fetch('get-certifications.php?type=certifications')
        .then(response => response.json())
        .then(data => {
          certOptionsDiv.innerHTML = ''; // 清空選項

          // 1. 添加「全選」選項
          var selectAllLabel = document.createElement('label');
          var selectAllCheckbox = document.createElement('input');
          selectAllCheckbox.type = 'checkbox';
          selectAllCheckbox.id = 'select-all-certifications';
          selectAllLabel.appendChild(selectAllCheckbox);
          selectAllLabel.appendChild(document.createTextNode(' 全選'));
          certOptionsDiv.appendChild(selectAllLabel);

          // 2. 生成證照選項
          data.forEach(file => {
            var label = document.createElement('label');
            var optCheckbox = document.createElement('input');
            optCheckbox.type = 'checkbox';
            optCheckbox.className = 'cert-checkbox'; // 標記所有選項
            optCheckbox.value = file.organization;
            label.appendChild(optCheckbox);
            label.appendChild(document.createTextNode(' ' + file.organization));
            certOptionsDiv.appendChild(label);
          });

          // 3. 監聽「全選」勾選事件
          selectAllCheckbox.addEventListener('change', function() {
            var checkboxes = certOptionsDiv.querySelectorAll('.cert-checkbox');
            checkboxes.forEach(chk => chk.checked = selectAllCheckbox.checked);
            updateCertOutput();
          });

          // 4. 當使用者手動取消某個選項時，自動取消「全選」
          certOptionsDiv.addEventListener('change', function(event) {
            if (event.target.classList.contains('cert-checkbox')) {
              var checkboxes = certOptionsDiv.querySelectorAll('.cert-checkbox');
              var allChecked = Array.from(checkboxes).every(chk => chk.checked);
              selectAllCheckbox.checked = allChecked;
              updateCertOutput();
            }
          });

        })
        .catch(error => console.error('取得證照清單錯誤:', error));
    } else {
        certContainer.style.display = 'none';
        certOptionsDiv.innerHTML = '';
        certOutputDiv.innerText = '';
      } 
  });

  // 更新「選取的證照」輸出內容
  function updateCertOutput() {
    var selectedCerts = [];
    certOptionsDiv.querySelectorAll('.cert-checkbox:checked').forEach(function(chk) {
      selectedCerts.push(chk.value);
    });
    certOutputDiv.innerText = selectedCerts.length > 0 ? '選取的證照：' + selectedCerts.join(', ') : '';
  }
});

            </script>
            <li class="list-group-item" data-value="language">
              <input type="checkbox" name="options[]" value="language"> 語言能力證明
            </li>
            <li class="list-group-item" data-value="topics">
              <input type="checkbox" name="options[]" value="topics"> 專題資料
            </li>
            <li class="list-group-item" data-value="other">
              <input type="checkbox" name="options[]" value="other"> 其他資料
            </li>
            <li class="list-group-item" data-value="read">
              <input type="checkbox" name="options[]" value="read"> 讀書計畫
            </li>
          </ul>
        </div>
        <input type="hidden" id="sorted-options" name="sorted_options">
        <button type="submit" class="btn btn-primary">匯出檔案(.doc)</button>
        <button type="button" class="btn btn-secondary" onclick="window.history.back();">返回上一頁</button>
      </form>
    </div>
  </div>
</section>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script>
  var sortable = new Sortable(document.getElementById('sortable-list'), {
    animation: 150,
    ghostClass: 'sortable-ghost'
  });

  document.getElementById('export-form').addEventListener('submit', function() {
    var sortedValues = [];
    document.querySelectorAll('#sortable-list li input[type="checkbox"]:checked').forEach(function(checkbox) {
      sortedValues.push(checkbox.value);
    });
    document.getElementById('sorted-options').value = sortedValues.join(',');
  });
</script>

<style>
  .list-group-item { cursor: move; }
  .sortable-ghost { opacity: 0.5; background: #f0f0f0; }
   /* 相關證照選項間距調整 */
   .certifications-options label {
      display: inline-block;
      margin: 5px; /* 上下左右皆有 5px 間距 */
      padding: 5px;
      border: 1px solid #ccc;
      border-radius: 4px;
      cursor: pointer;
    }
    .autobiography-options label {
    display: inline-block;
    margin: 5px;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
  }
</style>

<!-- ========================= footer start ========================= -->
<footer class="footer pt-100">
  <div class="container">
    <div class="row">
      <div class="col-xl-3 col-lg-4 col-md-6">
        <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
          <a href="index-04.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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
              justify-content: space-between;
            }
            .footer-widget {
              text-align: right;
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
