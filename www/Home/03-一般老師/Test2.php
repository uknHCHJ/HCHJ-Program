<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>落點分析</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* 省略的樣式內容與原版本相同 */
    </style>
</head>
<body>
    <div class="container">
        <h1>落點分析</h1>
        <form method="POST">
            <label for="chinese">國文：</label>
            <input type="number" name="chinese" id="chinese" min="0" max="100" required>
            <label for="english">英文：</label>
            <input type="number" name="english" id="english" min="0" max="100" required>
            <button type="submit">分析</button>
        </form>
        <hr>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 取得學生成績
            $chinese = intval($_POST['chinese']);
            $english = intval($_POST['english']);
            $totalScore = $chinese + $english;

            // 資料庫連線
            $host = '127.0.0.1';
            $db = 'HCHJ';
            $user = 'HCHJ';
            $pass = 'xx435kKHq';

            $conn = new mysqli($host, $user, $pass, $db);

            if ($conn->connect_error) {
                die("資料庫連線失敗：" . $conn->connect_error);
            }

            // 抓取學校資料
            $sql = "SELECT school_name, min_score FROM University";
            $result = $conn->query($sql);

            $schools = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $probability = max(0, min(100, ($totalScore / $row['min_score']) * 100));
                    $schools[] = [
                        'school_name' => $row['school_name'],
                        'min_score' => $row['min_score'],
                        'probability' => $probability
                    ];
                }
            }

            // 依錄取機率排序
            usort($schools, function ($a, $b) {
                return $b['probability'] <=> $a['probability'];
            });

            // 顯示學校錄取結果
            foreach ($schools as $index => $school) {
                echo "<div class='school-container'>";
                echo "<div class='school-name'>{$school['school_name']}</div>";
                echo "<div class='progress'>";
                echo "<progress value='{$school['probability']}' max='100'></progress>";
                echo "<span>" . round($school['probability'], 2) . "%</span>";
                echo "</div>";
                echo "<canvas id='chart-{$index}' class='chart'></canvas>";
                echo "</div>";
            }

            $conn->close();
        }
        ?>
    </div>

    <script>
        const chartData = <?php echo json_encode($schools ?? []); ?>;

        chartData.forEach((school, index) => {
            const ctx = document.getElementById(`chart-${index}`).getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['錄取機率', '未錄取機率'],
                    datasets: [{
                        data: [school.probability, 100 - school.probability],
                        backgroundColor: ['#4CAF50', '#F44336']
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    responsive: false,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>
</html>
