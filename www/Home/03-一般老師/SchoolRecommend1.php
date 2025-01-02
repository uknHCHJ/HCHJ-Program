<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>資管科學校排序</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>資管科學校排序</h1>
    <form id="scoreForm">
        <label for="chinese">國文成績：</label>
        <input type="number" id="chinese" name="chinese" required><br><br>
        <label for="english">英文成績：</label>
        <input type="number" id="english" name="english" required><br><br>
        <label for="math">數學成績：</label>
        <input type="number" id="math" name="math" required><br><br>
        <label for="professional">專業科目成績：</label>
        <input type="number" id="professional" name="professional" required><br><br>
        <button type="submit">計算排序</button>
    </form>

    <h2>學校排序結果</h2>
    <canvas id="schoolRankingChart" width="400" height="400"></canvas>

    <script>
        document.getElementById("scoreForm").addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = {
                chinese: parseFloat(formData.get('chinese')),
                english: parseFloat(formData.get('english')),
                math: parseFloat(formData.get('math')),
                professional: parseFloat(formData.get('professional'))
            };

            // 發送POST請求到後端
            const response = await fetch('SchoolRecommend2.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            // 顯示排序結果
            const labels = result.map(school => school.name);
            const scores = result.map(school => school.calculatedScore);

            // 繪製圖表
            const ctx = document.getElementById("schoolRankingChart").getContext("2d");
            new Chart(ctx, {
                type: "bar", // 以橫條圖顯示
                data: {
                    labels: labels,
                    datasets: [{
                        label: "學校加權分數",
                        data: scores,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)', // 藍色，透明度較低
                        borderColor: 'rgba(54, 162, 235, 1)', // 邊框顏色
                        borderWidth: 1
                    }],
                },
                options: {
                    indexAxis: 'y',  // 橫條圖顯示
                    scales: {
                        x: {
                            beginAtZero: true, // 讓 X 軸從 0 開始
                        },
                    },
                },
            });
        });
    </script>
</body>
</html>
