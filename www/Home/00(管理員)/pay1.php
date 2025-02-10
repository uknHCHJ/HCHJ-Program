<?php
session_start();
include 'db.php'; // 若有需要可利用此檔案確認登入或取得資料庫連線設定

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user'];
?>
<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>查看繳交狀況</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" type="image/x-icon" href="schoolimages/ukn.png">
  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/bootstrap-5.0.0-alpha.min.css">
  <link rel="stylesheet" href="assets/css/LineIcons.2.0.css">
  <link rel="stylesheet" href="assets/css/animate.css">
  <link rel="stylesheet" href="assets/css/tiny-slider.css">
  <link rel="stylesheet" href="assets/css/glightbox.min.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    /* 下拉選單與表格樣式 */
    #table-select {
      width: 100%;
      max-width: 600px;
      margin: 20px auto;
    }
    .table-container {
      max-width: 600px;
      margin: 20px auto;
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      padding: 10px;
      border: 1px solid #ccc;
      text-align: center;
    }
    th {
      background-color: #6A7C92;
      color: white;
    }
    td {
      background-color: #f9f9f9;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .table-container {
      animation: fadeIn 1s ease-in-out;
    }
    /* 載入動畫 */
    #loading {
      display: none;
      position: absolute;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 10;
      justify-content: center;
      align-items: center;
      color: #fff;
      font-size: 20px;
    }
    #loading:before {
      content: "";
      display: inline-block;
      border-radius: 4px;
      width: 50px;
      height: 50px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #fff;
      animation: spin 1s linear infinite;
      margin-right: 10px;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <!-- 頁首、導覽列等區塊可依需求放置 -->
  <header class="header navbar-area">
    <!-- 此處可放入導覽列內容 -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <nav class="navbar navbar-expand-lg">
            <a class="navbar-brand" href="index-04.php">
              <img src="schoolimages/uknlogo.png" alt="Logo">
            </a>
            <!-- 略其他選單項目 -->
            <ul class="navbar-nav ml-auto">
              <li class="nav-item">
                <a class="nav-link">目前登入：<?php echo $userId; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../logout.php">登出</a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </header>
  
  <!-- 主內容：班級下拉選單與資料表 -->
  <section class="service-section pt-130 pb-100">
    <div class="container">
      <div class="row">
        <div class="col-xl-6 col-lg-7 col-md-9 mx-auto">
          <div class="section-title text-center mb-55">
            <span class="wow fadeInDown" data-wow-delay=".2s">繳交名單</span>
          </div>
          <select id="table-select" class="form-select mb-4" onchange="fetchStudentData()">
            <option value="">請選擇...</option>
            <option value="A">資五忠</option>
            <option value="B">資五孝</option>
            <option value="C">資五仁</option>
            <option value="D">資五愛</option>    
          </select>  
        </div>
      </div>
      <!-- 載入動畫 -->
      <div id="loading">
        <span>正在載入資料...</span>
      </div>
      <!-- 資料表 -->
      <div class="table-container">
        <table id="data-table">
          <thead>
            <tr>
              <th>學號</th>
              <th>姓名</th>
              <th>繳交資訊</th>
            </tr>
          </thead>
          <tbody>
            <!-- 動態填入資料 -->
          </tbody>
        </table>
      </div>
    </div>
  </section>
  
  <!-- JavaScript -->
  <script>
    // 當選擇班級後，呼叫 pay2.php 取得該班學生資料
    function fetchStudentData() {
      var selectedClass = document.getElementById('table-select').value;
      if (!selectedClass) {
        alert("請選擇班級！");
        return;
      }
      // 顯示載入動畫
      document.getElementById("loading").style.display = "flex";
      
      fetch('pay2.php?class=' + selectedClass)
        .then(response => {
          if (!response.ok) {
            throw new Error('無法取得資料：' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          updateStudentTable(data);
          document.getElementById("loading").style.display = "none";
        })
        .catch(error => {
          console.error('錯誤:', error);
          alert('獲取資料失敗，請稍後再試！');
          document.getElementById("loading").style.display = "none";
        });
    }

    // 更新表格內容：依據後端回傳的學生資料陣列
    function updateStudentTable(data) {
      var tbody = document.getElementById('data-table').getElementsByTagName('tbody')[0];
      tbody.innerHTML = '';
      if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3">查無資料</td></tr>';
        return;
      }
      data.forEach(function(item) {
        var row = tbody.insertRow();
        row.insertCell(0).textContent = item.student_id;
        row.insertCell(1).textContent = item.name;
        var statusCell = row.insertCell(2);
        if(item.submission_status === '已繳交') {
          statusCell.innerHTML = '<span style="color: green;">已繳交</span>';
        } else {
          statusCell.innerHTML = '<span style="color: red;">未繳交</span>';
        }
      });
    }
  </script>
  <!-- 可加入其他 JS 檔案 -->
  <script src="assets/js/bootstrap.bundle-5.0.0.alpha-min.js"></script>
  <script src="assets/js/main.js"></script>
</body>
</html>
