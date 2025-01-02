<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Admission Probabilities</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <canvas id="admissionChart" width="400" height="400"></canvas>
    <script>
        // 從 PHP 傳入學校及機率數據
        const schoolData = <?php echo json_encode($schoolProbabilities); ?>;

        // 提取名稱和機率
        const schoolNames = schoolData.map(s => s.name);
        const probabilities = schoolData.map(s => s.probability);

        // 繪製圓餅圖
        const ctx = document.getElementById('admissionChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: schoolNames,
                datasets: [{
                    label: 'Admission Probabilities',
                    data: probabilities,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) => {
                                return `${tooltipItem.label}: ${tooltipItem.raw.toFixed(2)}%`;
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>