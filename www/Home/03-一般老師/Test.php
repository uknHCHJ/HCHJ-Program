<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二技錄取機率推薦</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>二技錄取機率推薦系統</h1>
    <form id="scoreForm">
        <label for="chinese">國文成績：</label>
        <input type="number" id="chinese" name="chinese" required><br><br>
        <label for="english">英文成績：</label>
        <input type="number" id="english" name="english" required><br><br>
        <label for="math">數學成績：</label>
        <input type="number" id="math" name="math" required><br><br>
        <label for="professional">專業科目成績：</label>
        <input type="number" id="professional" name="professional" required><br><br>
        <button type="submit">計算錄取機率</button>
    </form>

    <h2>推薦結果</h2>
    <canvas id="topSchoolsChart" width="400" height="400"></canvas>

    <script>
        document.getElementById("scoreForm").addEventListener("submit", async function (e) {
            e.preventDefault();

            const formData = new FormData(e.target);

            // 發送數據到後端
            const response = await fetch("Test2.php", {
                method: "POST",
                body: formData,
            });

            const data = await response.json();

            // 只保留前6個學校
            const topSchools = data.topSchools.slice(0, 6);

            // 計算總分，並將每個學校的分數轉換為百分比
            const totalScore = topSchools.reduce((sum, school) => sum + school.weightedScore, 0);
            topSchools.forEach(school => {
                school.percentage = (school.weightedScore / totalScore) * 100;
            });

            // 顏色稍微淡一點：降低透明度至 0.7
            const colors = [];
            const colorPalette = [
                { r: 54, g: 162, b: 235 },   // 藍色
                { r: 255, g: 99, b: 132 },   // 紅色
                { r: 75, g: 192, b: 192 },   // 綠色
                { r: 153, g: 102, b: 255 },  // 紫色
                { r: 255, g: 159, b: 64 },   // 橙色
                { r: 255, g: 205, b: 86 },   // 黃色
            ];

            // 生成顏色，降低透明度至 0.7
            for (let i = 0; i < topSchools.length; i++) {
                const color = colorPalette[i % colorPalette.length];
                colors.push(`rgba(${color.r}, ${color.g}, ${color.b}, 0.7)`); // 較淡的顏色
            }

            // 繪製圓餅圖
            const ctx = document.getElementById("topSchoolsChart").getContext("2d");
            new Chart(ctx, {
                type: "pie",
                data: {
                    labels: topSchools.map((school) => school.name),
                    datasets: [{
                        label: "錄取機率",
                        data: topSchools.map((school) => school.percentage),
                        backgroundColor: colors,
                        borderColor: colors.map(color => color.replace("rgba", "rgb").replace(", 0.7)", ")")), // 邊框顏色稍微深一點
                        borderWidth: 1
                    }],
                },
                options: {
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return `${context.label}: ${context.raw.toFixed(2)}%`;
                                },
                            },
                        },
                    },
                },
            });
        });
    </script>
</body>
</html>
