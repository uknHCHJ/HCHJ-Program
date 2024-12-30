<?php
// 接收結果
$chinese = isset($_GET['chinese']) ? (int)$_GET['chinese'] : 0;
$english = isset($_GET['english']) ? (int)$_GET['english'] : 0;
$math = isset($_GET['math']) ? (int)$_GET['math'] : 0;
$professional = isset($_GET['professional']) ? (int)$_GET['professional'] : 0;
$admission_chance = isset($_GET['chance']) ? htmlspecialchars($_GET['chance']) : '未知';
$total_score = isset($_GET['score']) ? (int)$_GET['score'] : 0;
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>錄取機率結果</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            color: #333;
        }
        .chart-container {
            width: 50%;
            margin: auto;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>您的錄取機率：<?php echo $admission_chance; ?></h1>
    <h2>總分：<?php echo $total_score; ?></h2>

    <div class="chart-container">
        <canvas id="scoreChart"></canvas>
    </div>

    <script>
        // 獲取各科成績
        const chinese = <?php echo $chinese; ?>;
        const english = <?php echo $english; ?>;
        const math = <?php echo $math; ?>;
        const professional = <?php echo $professional; ?>;

        // 準備圖表數據
        const data = {
            labels: ['國文', '英文', '數學', '專業科目'],
            datasets: [{
                label: '成績分佈',
                data: [chinese, english, math, professional],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        };

        // 配置圖表
        const config = {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '各科成績分佈'
                    }
                }
            },
        };

        // 渲染圖表
        window.onload = function() {
            const ctx = document.getElementById('scoreChart').getContext('2d');
            new Chart(ctx, config);
        };
    </script>
</body>
</html>
