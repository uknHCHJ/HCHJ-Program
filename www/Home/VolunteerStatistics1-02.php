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

// 判斷是否為 AJAX 請求
if (isset($_GET['school_id'])) {
    $school_id = intval($_GET['school_id']);

    // 查詢該學校的科系及其被選擇的次數
    $sql_departments = "
        SELECT d.id AS department_id, d.department_name, COUNT(p.department_id) AS student_count
        FROM Departments d
        LEFT JOIN Preferences p ON d.id = p.department_id
        WHERE d.school_id = $school_id
        GROUP BY d.id, d.department_name
        ORDER BY student_count DESC";
    
    $result_departments = $conn->query($sql_departments);

    $departments_data = [];
    if ($result_departments->num_rows > 0) {
        while ($row = $result_departments->fetch_assoc()) {
            $departments_data[] = [
                "department_name" => $row['department_name'],
                "student_count" => $row['student_count']
            ];
        }
    }
    echo json_encode($departments_data);
    exit;
}

// 1. 從 Preferences 表中取得資料
$sql_preferences = "SELECT school_id, user FROM Preferences";
$result_preferences = $conn->query($sql_preferences);

// 儲存 Preferences 資料
$preferences = [];
if ($result_preferences->num_rows > 0) {
    while ($row = $result_preferences->fetch_assoc()) {
        $preferences[] = $row;
    }
}

// 2. 從 user 表中取得資料，篩選 grade = 5 並且 class = 'B'
$sql_users = "SELECT user FROM user WHERE grade = 5 AND class = 'B'";
$result_users = $conn->query($sql_users);

// 儲存符合條件的使用者
$valid_users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $valid_users[] = $row['user'];
    }
}

// 3. 將兩表進行匹配並統計每所學校被選擇的次數
$school_counts = [];
foreach ($preferences as $preference) {
    if (in_array($preference['user'], $valid_users)) {
        $school_id = $preference['school_id'];
        if (!isset($school_counts[$school_id])) {
            $school_counts[$school_id] = 0;
        }
        $school_counts[$school_id]++;
    }
}

// 4. 取得學校名稱
$sql_schools = "SELECT school_id, school_name FROM School";
$result_schools = $conn->query($sql_schools);

// 學校名稱對照表
$school_names = [];
if ($result_schools->num_rows > 0) {
    while ($row = $result_schools->fetch_assoc()) {
        $school_names[$row['school_id']] = $row['school_name'];
    }
}

// 5. 組合資料並準備長條圖
$chart_data = [];
foreach ($school_counts as $school_id => $count) {
    $school_name = isset($school_names[$school_id]) ? $school_names[$school_id] : "未知學校";
    $chart_data[] = ["school_id" => $school_id, "school_name" => $school_name, "count" => $count];
}

// 關閉資料庫連線
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>長條圖 - 志願統計</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>班級志願統計 - 長條圖</h1>
    <canvas id="barChart" width="800" height="400"></canvas>
    <canvas id="departmentChart" width="800" height="400" style="margin-top: 50px;"></canvas>

    <script>
        // 從 PHP 傳遞資料到 JavaScript
        const chartData = <?php echo json_encode($chart_data); ?>;

        // 提取學校名稱和人數
        const labels = chartData.map(data => data.school_name);
        const data = chartData.map(data => data.count);

        // 建立學校長條圖
        const ctx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '選擇人數',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 初始化科系長條圖
        let departmentChart;

        // 設置學校長條圖的點擊事件
        document.getElementById('barChart').onclick = function (evt) {
            const points = barChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
            if (points.length) {
                const index = points[0].index;
                const schoolName = chartData[index].school_name;
                const schoolId = chartData[index].school_id;

                // 發送 AJAX 請求以獲取該學校的科系統計數據
                fetch(`?school_id=${schoolId}`)
                    .then(response => response.json())
                    .then(departmentData => {
                        updateDepartmentChart(departmentData, schoolName);
                    });
            }
        };

        // 更新或創建科系長條圖
        function updateDepartmentChart(departmentData, schoolName) {
            const labels = departmentData.map(data => data.department_name);
            const data = departmentData.map(data => data.student_count);

            if (departmentChart) {
                departmentChart.data.labels = labels;
                departmentChart.data.datasets[0].data = data;
                departmentChart.update();
            } else {
                const ctx = document.getElementById('departmentChart').getContext('2d');
                departmentChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: `科系選擇人數 (${schoolName})`,
                            data: data,
                            backgroundColor: 'rgba(153, 102, 255, 0.2)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }
    </script>
</body>
</html>

<!-- ========================= client-logo-section end ========================= -->

<!-- ========================= footer start ========================= -->
<footer class="footer pt-100">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="footer-widget mb-60 wow fadeInLeft" data-wow-delay=".2s">
                    <a href="index-03.php" class="logo mb-30"><img src="schoolimages/uknlogo.png" alt="logo"></a>
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