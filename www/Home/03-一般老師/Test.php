<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>統測入取機率計算</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- 引入 Chart.js 圖表庫 -->
</head>
<body>

    <h1>輸入您的統測成績</h1>
    <!-- 成績輸入表單 -->
    <form id="scoreForm" method="POST" action="SchoolRecommend2.php">
        <label for="chinese">國文：</label><input type="number" id="chinese" name="chinese" required><br><br>
        <label for="english">英文：</label><input type="number" id="english" name="english" required><br><br>
        <label for="math">數學：</label><input type="number" id="math" name="math" required><br><br>
        <label for="professional">專業科目：</label><input type="number" id="professional" name="professional" required><br><br>
        <button type="submit">計算入取機率</button>
    </form>

    <!-- 圓餅圖顯示區域 -->
    <h2>入取機率圓餅圖</h2>
    <canvas id="probabilityChart" width="400" height="400"></canvas>

    <?php
    // 這是後端 PHP 代碼
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // 接收表單數據
        $chinese = $_POST['chinese'];
        $english = $_POST['english'];
        $math = $_POST['math'];
        $professional = $_POST['professional'];

        // 假設計算入取機率（這裡是假的邏輯，您可以根據您的需要調整）
        $probabilities = [
            ['name' => '國立台北商業大學', 'probability' => ($chinese + $english + $math + $professional) / 400 * 100],
            ['name' => '中國科技大學', 'probability' => ($chinese + $english + $math) / 300 * 100],
            ['name' => '勤益科技大學', 'probability' => ($english + $math + $professional) / 300 * 100]
        ];

        // 將結果傳遞給 JavaScript 顯示圓餅圖
        echo "<script>";
        echo "var probabilities = " . json_encode($probabilities) . ";"; // PHP 轉換為 JS 數據
        echo "showChart(probabilities);"; // 執行 JavaScript 顯示圖表
        echo "</script>";
    }
    ?>

    <script>
        // 用來顯示圓餅圖的函數
        function showChart(probabilities) {
            const ctx = document.getElementById('probabilityChart').getContext('2d');

            // 取得學校名稱與入取機率
            const labels = probabilities.map(p => p.name);
            const data = probabilities.map(p => p.probability);

            // 圓餅圖設定
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '入取機率',
                        data: data,
                        backgroundColor: ['#FF5733', '#33FF57', '#3357FF'],
                        borderColor: '#fff',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }
    </script>

</body>
</html>
