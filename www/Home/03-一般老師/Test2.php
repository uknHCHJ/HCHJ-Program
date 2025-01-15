<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>落點分析</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #f9f9f9;
        }
        .container {
            width: 80%;
            max-width: 800px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        h1 {
            font-size: 24px;
        }
        form {
            margin-bottom: 20px;
        }
        label, input {
            margin: 5px 0;
        }
        input[type="number"] {
            width: 80px;
            padding: 5px;
            margin: 0 10px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .school-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .school-name {
            width: 50%;
            font-size: 18px;
            font-weight: bold;
        }
        canvas {
            width: 150px;
            height: 150px;
        }
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

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

            // 從資料庫抓取學校資料
            $sql = "SELECT school_name, min_score FROM School";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $probability = max(0, min(100, ($totalScore / $row['min_score']) * 100));

                    // 顯示學校名稱和圓餅圖
                    echo "<div class='school-container'>";
                    echo "<div class='school-name'>{$row['school_name']}</div>";
                    echo "<canvas id='chart-{$row['school_name']}' width='150' height='150'></canvas>";
                    echo "</div>";

                    // 用 Chart.js 顯示圓餅圖
                    echo "<script>
                        var ctx = document.getElementById('chart-{$row['school_name']}').getContext('2d');
                        new Chart(ctx, {
                            type: 'doughnut',
                            data: {
                                labels: ['錄取機率', '未錄取機率'],
                                datasets: [{
                                    data: [{$probability}, 100 - {$probability}],
                                    backgroundColor: ['#4CAF50', '#F44336']
                                }]
                            },
                            options: {
                                responsive: false,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    </script>";
                }
            } else {
                echo "沒有找到學校資料。";
            }

            $conn->close();
        }
        ?>
    </div>
</body>
</html>
