<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 1. 從 Preferences 表中取得資料並聯接 user 表獲取姓名
$sql_preferences = "SELECT p.school_id, p.user, u.name FROM Preferences p JOIN user u ON p.user = u.user";
$result_preferences = $conn->query($sql_preferences);

// 儲存 Preferences 資料
$preferences = [];
if ($result_preferences->num_rows > 0) {
    while ($row = $result_preferences->fetch_assoc()) {
        $preferences[] = $row; // 儲存每一筆資料，包含學號與姓名
    }
}

// 2. 從 user 表中取得資料，篩選 grade = 5 並且 class = 'B'
$sql_users = "SELECT user FROM user WHERE grade = 5 AND class = 'B'";
$result_users = $conn->query($sql_users);

// 儲存符合條件的使用者
$valid_users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $valid_users[] = $row['user']; // 只儲存使用者的 user 欄位
    }
}

// 3. 將兩表進行匹配並統計每所學校被選擇的次數
$school_counts = [];
foreach ($preferences as $preference) {
    if (in_array($preference['user'], $valid_users)) {
        // 如果此使用者在符合條件的使用者中
        $school_id = $preference['school_id'];
        $user_name = $preference['name']; // 儲存使用者的姓名
        if (!isset($school_counts[$school_id])) {
            $school_counts[$school_id] = [
                'count' => 0, // 初始化學校計數
                'users' => [] // 儲存使用者姓名
            ];
        }
        $school_counts[$school_id]['count']++;
        $school_counts[$school_id]['users'][] = $user_name; // 將使用者姓名加入
    }
}

// 4. 取得學校名稱
$sql_schools = "SELECT school_id, school_name FROM School";
$result_schools = $conn->query($sql_schools);

// 學校名稱對照表
$school_names = [];
if ($result_schools->num_rows > 0) {
    while ($row = $result_schools->fetch_assoc()) {
        $school_names[$row['school_id']] = $row['school_name']; // 以 school_id 為鍵，school_name 為值
    }
}

// 5. 組合資料並準備長條圖
$chart_data = [];
foreach ($school_counts as $school_id => $count_data) {
    $school_name = isset($school_names[$school_id]) ? $school_names[$school_id] : "未知學校";
    $chart_data[] = [
        "school_id" => $school_id, // 傳遞 school_id
        "school_name" => $school_name,
        "count" => $count_data['count'],
        "users" => $count_data['users'] // 傳遞使用者姓名
    ];
}

// 關閉資料庫連線
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
                                    <a class="page-scroll" href="student02-1.php">學生管理</a>
                                    <ul class="sub-menu">
                                        <li class="nav-item"><a href="VolunteerStatistics1-02.php">志願序統計</a></li>
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>長條圖 - 志願統計</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            margin: 20px auto;
            max-width: 1000px;
        }

        canvas {
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <h1>班級志願統計 - 長條圖</h1>
    <div class="chart-container">
    <canvas id="barChart" width="1000" height="500"></canvas> <!-- 設定高度為 500 -->
</div>


    <h2>科系志願統計</h2>
    <div class="chart-container">
        <canvas id="departmentChart" width="1000" height="500"></canvas><!-- 設定高度為 500 -->
    </div>
</body>


<script>
    // 從 PHP 傳遞資料到 JavaScript
    const chartData = <?php echo json_encode($chart_data); ?>;
    console.log("chartData:", chartData); // 除錯用

    // 提取學校名稱和人數
    const labels = chartData.map(data => data.school_name);
    const data = chartData.map(data => data.count);

    console.log("Labels:", labels); // 除錯用
    console.log("Data:", data);     // 除錯用

    // 初始化學校長條圖
    const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: '選擇人數',
                data: data,
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            aspectRatio: 1.5,//修改長寬比例
            scales: {
                y: {
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        afterLabel: function (context) {
                            const index = context.dataIndex;
                            const studentNames = studentNamesList[index];
                            if (studentNames && Array.isArray(studentNames)) {
                                return '學生姓名:\n' + studentNames.join('\n');
                            } else {
                                return '學生姓名: 無資料';
                            }
                        }
                    }
                }
            }
        }
    });

    // 初始化科系圖表
    const departmentCtx = document.getElementById('departmentChart').getContext('2d');
    let departmentChart;

    document.getElementById('barChart').onclick = function (evt) {
        const points = barChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);

        if (points.length) {
            const index = points[0].index;
            const selectedSchool = chartData[index];
            const schoolId = selectedSchool.school_id;

            // AJAX 獲取科系數據
            fetch(`get_departments2-02.php?school_id=${schoolId}`)
                .then(response => response.json())
                .then(departmentData => {
                    const labels = departmentData.map(data => data.department_name);
                    const data = departmentData.map(data => data.count);
                    const studentNamesList = departmentData.map(data => data.student_names);

                    if (departmentChart) {
                        departmentChart.destroy();
                    }
                    departmentChart = new Chart(departmentCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: '選擇人數',
                                data: data,
                                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            aspectRatio: 2,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        afterLabel: function (context) {
                                            const index = context.dataIndex;
                                            const studentNames = studentNamesList[index];
                                            return '學生姓名: ' + studentNames;
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
        }
    };


</script>
</body>

</html>