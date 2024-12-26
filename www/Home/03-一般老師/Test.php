<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>學校推薦與機率</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>根據您的統測成績，這是您的學校推薦</h1>
    <canvas id="barChart" width="800" height="400"></canvas>

    <script>
        // 假設已經從 PHP 獲取的推薦資料
        const recommendations = <?php echo json_encode($recommendations); ?>;

        const labels = recommendations.map(school => school.school_name);
        const probabilities = recommendations.map(school => school.probability);

        const ctx = document.getElementById('barChart').getContext('2d');

        const barChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: '推薦機率 (%)',
                    data: probabilities,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>
