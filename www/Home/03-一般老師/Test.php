<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>推薦成功率</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>推薦結果</h1>
    <canvas id="successChart" width="400" height="400"></canvas>

    <script>
        // AJAX 請求取得數據
        fetch("Test2.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: new URLSearchParams({
                chinese: 90, // 測試數據
                english: 85,
                math: 88,
                professional: 92
            })
        })
        .then(response => response.json())
        .then(data => {
            const ctx = document.getElementById('successChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['成功學校', '未達門檻學校'],
                    datasets: [{
                        data: [data.qualified, data.total - data.qualified],
                        backgroundColor: ['#4CAF50', '#FF0000']
                    }]
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.raw;
                                    const total = data.total;
                                    const percentage = ((value / total) * 100).toFixed(2);
                                    return `${context.label}: ${percentage}%`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
