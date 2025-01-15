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

// 1. 從 Preferences 表中取得資料
$sql_preferences = "SELECT school_id, department_id, user FROM Preferences";
$result_preferences = $conn->query($sql_preferences);

// 儲存 Preferences 資料
$preferences = [];
if ($result_preferences->num_rows > 0) {
    while ($row = $result_preferences->fetch_assoc()) {
        $preferences[] = $row; // 儲存每一筆資料
    }
}

// 2. 從 user 表中取得資料，篩選 grade = 5 並且 class = 'B'
$sql_users = "SELECT user, name FROM user WHERE grade = 5 AND class = 'B'";
$result_users = $conn->query($sql_users);

// 儲存符合條件的使用者
$valid_users = [];
if ($result_users->num_rows > 0) {
    while ($row = $result_users->fetch_assoc()) {
        $valid_users[$row['user']] = $row['name']; // user 作為鍵，name 作為值
    }
}

// 3. 將 Preferences 表與篩選條件進行匹配，統計學校和科系的選擇次數與人員
$school_department_counts = [];
foreach ($preferences as $preference) {
    if (array_key_exists($preference['user'], $valid_users)) {
        $school_id = $preference['school_id'];
        $department_id = $preference['department_id'];
        $user_name = $valid_users[$preference['user']]; // 取得使用者名稱

        if (!isset($school_department_counts[$school_id])) {
            $school_department_counts[$school_id] = [];
        }
        if (!isset($school_department_counts[$school_id][$department_id])) {
            $school_department_counts[$school_id][$department_id] = [
                'count' => 0,
                'users' => []
            ];
        }
        $school_department_counts[$school_id][$department_id]['count']++;
        $school_department_counts[$school_id][$department_id]['users'][] = $user_name;
    }
}

// 4. 取得學校名稱和科系名稱
$sql_schools = "SELECT school_id, school_name FROM School";
$result_schools = $conn->query($sql_schools);

$school_names = [];
if ($result_schools->num_rows > 0) {
    while ($row = $result_schools->fetch_assoc()) {
        $school_names[$row['school_id']] = $row['school_name'];
    }
}

$sql_departments = "SELECT id, department_name FROM department";
$result_departments = $conn->query($sql_departments);

$department_names = [];
if ($result_departments->num_rows > 0) {
    while ($row = $result_departments->fetch_assoc()) {
        $department_names[$row['id']] = $row['department_name'];
    }
}

// 5. 整理資料供前端顯示
$chart_data = [];
foreach ($school_department_counts as $school_id => $departments) {
    $school_name = isset($school_names[$school_id]) ? $school_names[$school_id] : "未知學校";
    foreach ($departments as $department_id => $data) {
        $department_name = isset($department_names[$department_id]) ? $department_names[$department_id] : "未知科系";
        $chart_data[] = [
            'school_name' => $school_name,
            'department_name' => $department_name,
            'count' => $data['count'],
            'users' => implode(", ", $data['users']) // 將用戶名稱串接
        ];
    }
}

// 關閉資料庫連線
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>學校與科系選擇統計</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>學校與科系選擇統計</h1>

    <!-- 顯示數據表 -->
    <table border="1">
        <thead>
            <tr>
                <th>學校名稱</th>
                <th>科系名稱</th>
                <th>選擇人數</th>
                <th>選擇人員</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($chart_data as $data): ?>
                <tr>
                    <td><?php echo htmlspecialchars($data['school_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['department_name']); ?></td>
                    <td><?php echo htmlspecialchars($data['count']); ?></td>
                    <td><?php echo htmlspecialchars($data['users']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- 顯示長條圖 -->
    <canvas id="barChart" width="800" height="400"></canvas>
    <script>
        // 從 PHP 傳遞資料到 JavaScript
        const chartData = <?php echo json_encode($chart_data); ?>;

        // 提取數據
        const labels = chartData.map(data => `${data.school_name} - ${data.department_name}`);
        const dataCounts = chartData.map(data => data.count);

        // 建立長條圖
        const ctx = document.getElementById('barChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '選擇人數',
                    data: dataCounts,
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
    </script>
</body>
</html>
    <!-- ========================= service-section end ========================= -->
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