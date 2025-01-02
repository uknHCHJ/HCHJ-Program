<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>學校入取機率</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>二技學校入取機率</h2>
    <canvas id="myChart" width="400" height="400"></canvas>
    
    <script>
        var ctx = document.getElementById('myChart').getContext('2d');
        var data = {
            labels: <?php echo json_encode(array_column($schoolProbabilities, 'name')); ?>,  // 學校名稱
            datasets: [{
                data: <?php echo json_encode(array_column($schoolProbabilities, 'probability')); ?>,  // 入取機率
                backgroundColor: ['#FF9999', '#66B2FF', '#99FF99'], // 可調整顏色
                hoverOffset: 4
            }]
        };

        var myChart = new Chart(ctx, {
            type: 'pie',  // 圓餅圖
            data: data
        });
    </script>
</body>
</html>
