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
$sql_preferences = "SELECT school_id, user FROM Preferences";
$result_preferences = $conn->query($sql_preferences);

// 儲存 Preferences 資料
$preferences = [];
if ($result_preferences->num_rows > 0) {
    while ($row = $result_preferences->fetch_assoc()) {
        $preferences[] = $row; // 儲存每一筆資料
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
        if (!isset($school_counts[$school_id])) {
            $school_counts[$school_id] = 0; // 初始化學校計數
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
        $school_names[$row['school_id']] = $row['school_name']; // 以 school_id 為鍵，school_name 為值
    }
}

// 5. 組合資料並準備長條圖
$chart_data = [];
foreach ($school_counts as $school_id => $count) {
    $school_name = isset($school_names[$school_id]) ? $school_names[$school_id] : "未知學校";
    $chart_data[] = ["school_name" => $school_name, "count" => $count];
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

    <h2>科系志願統計</h2>
    <canvas id="departmentChart" width="800" height="400"></canvas>
    <script>
        // 從 PHP 傳遞資料到 JavaScript
        const chartData = <?php echo json_encode($chart_data); ?>;

        // 提取學校名稱和人數
        const labels = chartData.map(data => data.school_name);
        const data = chartData.map(data => data.count);

        // 建立長條圖
        const ctx = document.getElementById('barChart').getContext('2d');
        new Chart(ctx, {
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
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        let departmentChart;

        // 點擊學校時載入科系資料
        document.getElementById('barChart').onclick = function (evt) {
            const points = barChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, false);

            if (points.length) {
                const index = points[0].index;
                const selectedSchool = chartData[index];
                const schoolId = selectedSchool.school_id;

                // 使用 AJAX 請求該校科系資料
                fetch(`get_departments2-02.php?school_id=${schoolId}`)
                    .then(response => response.json())
                    .then(departmentData => {
                        const labels = departmentData.map(data => data.department_name);
                        const data = departmentData.map(data => data.count);

                        // 更新或創建科系長條圖
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
                    });
            }
        };


    </script>
</body>

</html>