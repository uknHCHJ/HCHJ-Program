<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>錄取分析結果</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 20px;
        }
        canvas {
            max-width: 600px;
            margin: auto;
        }
    </style>
</head>
<body>
    <h1>錄取分析結果</h1>
    <canvas id="admissionChart"></canvas>

    <?php
    // 連線資料庫
    $conn = new mysqli("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
    
    if ($conn->connect_error) {
        die("連線失敗: " . $conn->connect_error);
    }

    // 接收表單資料
    $chinese = $_POST['chinese'];
    $english = $_POST['english'];
    $math = $_POST['math'];
    $professional = $_POST['professional'];

    // 查詢學校和加權資訊
    $sql = "SELECT school_name, department, chinese_weight, english_weight, math_weight, professional_weight, total_threshold FROM school_thresholds";
    $result = $conn->query($sql);

    $schools = [];
    $admissibleSchools = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $weightedScore = (
                $row['chinese_weight'] * $chinese +
                $row['english_weight'] * $english +
                $row['math_weight'] * $math +
                $row['professional_weight'] * $professional
            );

            $schools[] = [
                'name' => $row['school_name'] . ' (' . $row['department'] . ')',
                'score' => $weightedScore,
                'threshold' => $row['total_threshold']
            ];

            if ($weightedScore >= $row['total_threshold']) {
                $admissibleSchools[] = $row['school_name'] . ' (' . $row['department'] . ')';
            }
        }
    }

    $conn->close();

    // 準備資料傳遞到前端
    echo "<script>\n";
    echo "const schools = " . json_encode($schools) . ";\n";
    echo "const admissibleSchools = " . json_encode($admissibleSchools) . ";\n";
    echo "</script>\n";
    ?>

    <script>
        const ctx = document.getElementById('admissionChart').getContext('2d');

        const data = {
            labels: schools.map(school => school.name),
            datasets: [{
                label: '加權分數',
                data: schools.map(school => school.score),
                backgroundColor: schools.map(school => school.score >= school.threshold ? 'rgba(75, 192, 192, 0.6)' : 'rgba(255, 99, 132, 0.6)'),
                borderColor: schools.map(school => school.score >= school.threshold ? 'rgba(75, 192, 192, 1)' : 'rgba(255, 99, 132, 1)'),
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const school = schools[tooltipItem.dataIndex];
                                return `${school.name}: ${school.score.toFixed(2)} (門檻: ${school.threshold})`;
                            }
                        }
                    }
                }
            }
        });

        // 顯示可錄取學校清單
        if (admissibleSchools.length > 0) {
            const admissibleDiv = document.createElement('div');
            admissibleDiv.innerHTML = `<h2>可錄取學校：</h2><ul>${admissibleSchools.map(school => `<li>${school}</li>`).join('')}</ul>`;
            document.body.appendChild(admissibleDiv);
        }// else {
           //const noAdmission = document.createElement('p');
           // noAdmission.textContent = '沒有符合條件的學校。';
           // document.body.appendChild(noAdmission);
      //  }
    </script>
</body>
</html>
