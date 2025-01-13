<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二技志願序統計</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #departmentChart {
            display: none; /* 初始隱藏科系的圖表 */
        }
    </style>
</head>
<body>
    <!-- 頁面標題 -->
    <section style="background-color: #f5f5f5; padding: 20px; text-align: center;">
        <h2>二技志願序統計</h2>
        <a href="index.php">首頁</a> > <a href="portfolio.php">二技校園網介紹</a>
    </section>

    <!-- 圖表區域 -->
    <div style="width: 80%; margin: 20px auto;">
        <canvas id="schoolChart" width="400" height="200"></canvas>
        <canvas id="departmentChart" width="400" height="200"></canvas>
    </div>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const schoolChartCtx = document.getElementById('schoolChart').getContext('2d');
            const departmentChartCtx = document.getElementById('departmentChart').getContext('2d');

            let schoolChart;
            let departmentChart;

            // 請求學校數據
            fetch('VolunteerStatistics2-02.php?action=getSchools')
                .then(response => response.json())
                .then(data => {
                    const labels = data.map(item => item.school_name);
                    const values = data.map(item => item.student_count);

                    // 初始化學校圖表
                    schoolChart = new Chart(schoolChartCtx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: '學生人數',
                                data: values,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            onClick: (e, elements) => {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const schoolId = data[index].school_id;
                                    showDepartmentChart(schoolId);
                                }
                            }
                        }
                    });
                });

            // 顯示科系圖表
            function showDepartmentChart(schoolId) {
                fetch(`dataHandler.php?action=getDepartments&school_id=${schoolId}`)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.map(item => item.department_name);
                        const values = data.map(item => item.student_count);

                        if (departmentChart) {
                            departmentChart.destroy();
                        }

                        departmentChart = new Chart(departmentChartCtx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: '學生人數',
                                    data: values,
                                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                    borderColor: 'rgba(153, 102, 255, 1)',
                                    borderWidth: 1
                                }]
                            }
                        });

                        document.getElementById('departmentChart').style.display = 'block';
                    });
            }
        });
    </script>
</body>
</html>
