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
<form action="" method="POST">
    <label for="chinese">國文：</label>
    <input type="number" name="chinese" id="chinese" required><br><br>

    <label for="english">英文：</label>
    <input type="number" name="english" id="english" required><br><br>

    <label for="math">數學：</label>
    <input type="number" name="math" id="math" required><br><br>

    <label for="special1">專業科目一：</label>
    <input type="number" name="special1" id="special1" required><br><br>

    <label for="special2">專業科目二：</label>
    <input type="number" name="special2" id="special2" required><br><br>

    <button type="submit">計算錄取率</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 接收學生輸入的各科成績
    $chinese = $_POST['chinese'];
    $english = $_POST['english'];
    $math = $_POST['math'];
    $special1 = $_POST['special1']; // 第一專業科目
    $special2 = $_POST['special2']; // 第二專業科目

    // 定義各科目的加權比例
    $weights = [
        'chinese' => 1.4, // 國文的加權比例
        'english' => 1.4, // 英文的加權比例
        'math' => 2.0,    // 數學的加權比例
        'special1' => 2.4,  // 第一專業科目的加權比例
        'special2' => 2.4   // 第二專業科目的加權比例
    ];

    // 計算加權後的總分
    $totalScore = ($chinese * $weights['chinese']) +
                  ($english * $weights['english']) +
                  ($math * $weights['math']) +
                  ($special1 * $weights['special1']) +
                  ($special2 * $weights['special2']);

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

    // 計算接近度（分數越高，接近度越高）
$results = [];
foreach ($schools as $school => $threshold) {
    // 計算分數和門檻的差距，差距越小，接近度越高
    $difference = abs($totalScore - $threshold); // 計算差距
    $maxDifference = 100; // 最大可能差距，可以根據需要調整

    // 動態計算接近度，避免固定除數過小，讓接近度合理
    $closeness = max(0, 1 - $difference / $maxDifference); // 調整接近度計算公式

    $results[$school] = $closeness;
}

// 檢查接近度結果
echo "<pre>";
echo "Total Score: " . $totalScore . "<br>";
echo "Results: ";
print_r($results);
echo "</pre>";

    if ($totalCloseness > 0) {
        // 計算占比
        $percentages = [];
        foreach ($topSchools as $school => $closeness) {
            $percentages[$school] = ($closeness / $totalCloseness) * 100;
        }

        // 顯示結果並準備傳送至前端
        echo "<div id=\"highestSchool\">您最有可能錄取的學校是：{$topSchools[0]} ({$percentages[array_keys($topSchools)[0]]}%)</div>";

        // 圓餅圖顯示
        echo "<canvas id='myChart'></canvas>";
        echo "<script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var results = " . json_encode($percentages) . ";
            var labels = Object.keys(results);
            var data = Object.values(results);

            var colors = [];
            for (var i = 0; i < data.length; i++) {
                if (i === 0) {
                    colors.push('#FF5733'); // 高接近度顯示顯眼的顏色
                } else {
                    colors.push('#36A2EB'); // 其他學校使用較冷的顏色
                }
            }

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
    } else {
        echo "<div id=\"highestSchool\">無法計算錄取率，請檢查輸入的成績。</div>";
    }
}
?>

</body>
</html>
