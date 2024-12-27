<?php
// 開啟錯誤顯示
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 檢查是否有 POST 請求
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 檢查是否成功接收表單數據
    if (isset($_POST['chinese'], $_POST['english'], $_POST['math'], $_POST['professional'])) {
        $chinese = $_POST['chinese'];  // 國文
        $english = $_POST['english'];  // 英文
        $math = $_POST['math'];        // 數學
        $professional = $_POST['professional'];  // 專業科目

        // 顯示收到的數據
        echo "<pre>";
        print_r($_POST);  // 打印出收到的表單數據
        echo "</pre>";

        // 假設計算入取機率（這裡是假的邏輯，您可以根據您的需求調整）
        $probabilities = [
            ['name' => '國立台北商業大學', 'probability' => ($chinese + $english + $math + $professional) / 400 * 100],
            ['name' => '中國科技大學', 'probability' => ($chinese + $english + $math) / 300 * 100],
            ['name' => '勤益科技大學', 'probability' => ($english + $math + $professional) / 300 * 100],
            ['name' => '虎尾科技大學', 'probability' => ($chinese + $math + $professional) / 300 * 100],
            ['name' => '致理科技大學', 'probability' => ($chinese + $english + $professional) / 300 * 100]
        ];

        // 將計算結果轉換為 JavaScript 可以讀取的格式
        echo "<script>";
        echo "var probabilities = " . json_encode($probabilities) . ";"; // PHP 轉換為 JavaScript 數據
        echo "console.log(probabilities);"; // 檢查數據是否正確
        echo "showChart(probabilities);"; // 呼叫顯示圖表的 JavaScript 函數
        echo "</script>";
    } else {
        echo "表單數據未完整提交，請檢查。";
    }
}
?>

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
    <form method="POST" action="">
        <label for="chinese">國文：</label><input type="number" id="chinese" name="chinese" required><br><br>
        <label for="english">英文：</label><input type="number" id="english" name="english" required><br><br>
        <label for="math">數學：</label><input type="number" id="math" name="math" required><br><br>
        <label for="professional">專業科目：</label><input type="number" id="professional" name="professional" required><br><br>
        <button type="submit">計算入取機率</button>
    </form>

    <!-- 圓餅圖顯示區域 -->
    <h2>入取機率圓餅圖</h2>
    <canvas id="probabilityChart" width="400" height="400"></canvas>

    <script>
        // 用來顯示圓餅圖的函數
        function showChart(probabilities) {
            console.log('probabilities:', probabilities); // 檢查數據是否正確傳遞到 JavaScript

            const ctx = document.getElementById('probabilityChart').getContext('2d');
            const labels = probabilities.map(p => p.name);
            const data = probabilities.map(p => p.probability);

            // 檢查 labels 和 data 是否正確
            console.log('Labels:', labels);
            console.log('Data:', data);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '入取機率',
                        data: data,
                        backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#FF33A1', '#FF8C33'],
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
