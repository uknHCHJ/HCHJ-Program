<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>五專資管科二技錄取率評估</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
            text-align: center;
        }
        input {
            padding: 8px;
            margin: 10px;
            width: 200px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        canvas {
            display: block;
            margin: 0 auto;
            max-width: 100%;
        }
        #highestSchool {
            text-align: center;
            font-size: 1.5em;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>五專資管科二技錄取率評估</h1>

<!-- 表單，讓學生輸入各科成績 -->
<form action="Test.php" method="POST">
    <label for="chinese">國文：</label>
    <input type="number" name="chinese" id="chinese" required><br><br>

    <label for="english">英文：</label>
    <input type="number" name="english" id="english" required><br><br>

    <label for="math">數學：</label>
    <input type="number" name="math" id="math" required><br><br>

    <label for="special">專業科目：</label>
    <input type="number" name="special" id="special" required><br><br>

    <button type="submit">計算錄取率</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 接收學生輸入的各科成績
    $chinese = $_POST['chinese'];
    $english = $_POST['english'];
    $math = $_POST['math'];
    $special = $_POST['special'];

    // 定義各科目的加權比例
    $weights = [
        'chinese' => 1.4, // 國文的加權比例
        'english' => 1.4, // 英文的加權比例
        'math' => 2.0,    // 數學的加權比例
        'special' => 2.4  // 專業科目的加權比例
    ];

    // 計算加權後的總分
    $totalScore = ($chinese * $weights['chinese']) +
                  ($english * $weights['english']) +
                  ($math * $weights['math']) +
                  ($special * $weights['special']);

    // 根據資料庫資料修改學校門檻
    $schools = [
        '國立高雄科技大學' => 280,
        '國立雲林科技大學' => 270,
        '國立臺中科技大學' => 260,
        '國立臺北商業大學' => 250,
        '勤益科技大學' => 240,
        '南臺科技大學' => 240,
        '健行科技大學' => 230,
        '崑山科技大學' => 230,
        '虎尾科技大學' => 230,
        '致理科技大學' => 220,
        '中國科技大學' => 220,
        '中華科技大學' => 210,
        '國北護大學' => 200,
        '台北海洋科技大學' => 200
    ];

    // 計算接近度（差距倒數）
    $results = [];
    foreach ($schools as $school => $threshold) {
        $difference = abs($totalScore - $threshold);
        $closeness = 1 / (1 + $difference); // 計算接近度（倒數，差距越小接近度越高）
        $results[$school] = $closeness;
    }

    // 排序學校（根據接近度高到低）
    arsort($results);

    // 取前五名學校（並按錄取門檻排序）
    $topSchools = array_slice($results, 0, 5, true);

    // 計算接近度總和
    $totalCloseness = array_sum($topSchools);

    // 計算占比
    $percentages = [];
    foreach ($topSchools as $school => $closeness) {
        $percentages[$school] = ($closeness / $totalCloseness) * 100;
    }

    // 顯示結果並準備傳送至前端
    echo "<script>
        var results = " . json_encode($percentages) . ";
        var labels = Object.keys(results);
        var data = Object.values(results);

        // 顯示學生最有可能錄取的學校
        var highestSchool = labels[0];
        var highestRate = data[0];
        document.write('<div id=\"highestSchool\">您最有可能錄取的學校是：' + highestSchool + ' (' + highestRate.toFixed(2) + '%機率)</div>');

        // 自訂顏色配置，讓最高錄取機率的學校顯得突出
        var colors = [];
        for (var i = 0; i < data.length; i++) {
            if (i === 0) {
                colors.push('#FF5733'); // 高接近度顯示顯眼的顏色
            } else {
                colors.push('#36A2EB'); // 其他學校使用較冷的顏色
            }
        }

        // 圓餅圖顯示
        var ctx = document.createElement('canvas');
        document.body.appendChild(ctx);
        var chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: '學校接近度占比 (%)',
                    data: data,
                    backgroundColor: colors,
                    hoverOffset: 4
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
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });
    </script>";
}
?>

</body>
</html>
