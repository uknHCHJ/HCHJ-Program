<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二技入學分析</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['學校', '錄取人數百分比'],
                <?php
                $servername = "127.0.0.1";  
                $username = "HCHJ";  
                $password = "xx435kKHq";  
                $dbname = "HCHJ";  

                // 建立連線
                $conn = new mysqli($servername, $username, $password, $dbname);

                // 檢查連線
                if ($conn->connect_error) {
                    die("連線失敗: " . $conn->connect_error);
                }

                // 查詢每個學校的錄取百分比
                $sql = "SELECT school_name, COUNT(*) AS num_students 
                        FROM schools 
                        INNER JOIN students ON students.score >= schools.min_score 
                        GROUP BY school_name";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "['" . $row['school_name'] . "', " . $row['num_students'] . "],";
                    }
                }

                $conn->close();
                ?>
            ]);

            var options = {
                title: '二技學校錄取百分比分析',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <h1>二技學校入學分析</h1>
    <div id="piechart" style="width: 900px; height: 500px;"></div>
</body>
</html>