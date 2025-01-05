<?php
// 資料庫連線
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 處理表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chinese = $_POST['chinese'];
    $english = $_POST['english'];
    $math = $_POST['math'];
    $professional = $_POST['professional'];

    // 獲取學校資料
    $sql = "SELECT * FROM school_thresholds";
    $result = $conn->query($sql);
    $school_data = [];
    $chart_data = [];

    if ($result->num_rows > 0) {
        while ($school = $result->fetch_assoc()) {
            // 計算加權總分
            $weighted_score = $chinese * $school['chinese_weight'] +
                              $english * $school['english_weight'] +
                              $math * $school['math_weight'] +
                              $professional * $school['professional_weight'];

            // 判斷是否錄取
            $is_admitted = $weighted_score >= $school['total_threshold'];

            // 儲存資料供前端顯示
            $school_data[] = [
                "school_name" => $school['school_name'],
                "department" => $school['department'],
                "weighted_score" => $weighted_score,
                "total_threshold" => $school['total_threshold'],
                "is_admitted" => $is_admitted
            ];

            $chart_data[] = [
                "label" => $school['school_name'] . " - " . $school['department'],
                "score" => $weighted_score,
                "threshold" => $school['total_threshold']
            ];
        }
    }

    // 將圖表數據轉為 JSON 格式
    $chart_data_json = json_encode($chart_data);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>錄取結果</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>錄取結果分析</h1>
    <p>下列圖表為您的加權分數與校園錄取門檻比較：</p>

    <canvas id="scoreChart" width="800" height="400"></canvas>

    <script>
        const chartData = <?= $chart_data_json ?>;

        // 提取圖表數據
        const labels = chartData.map(data => data.label);
        const studentScores = chartData.map(data => data.score);
        const thresholds = chartData.map(data => data.threshold);

        // 繪製圖表
        const ctx = document.getElementById('scoreChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: '您的加權分數',
                        data: studentScores,
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    },
                    {
                        label: '該校錄取門檻',
                        data: thresholds,
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: '統測加權分數與錄取門檻比較'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: '分數'
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
