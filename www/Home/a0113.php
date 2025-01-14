<?php
$servername = "127.0.0.1";
$dbUser = "HCHJ";
$dbPassword = "xx435kKHq";
$dbname = "HCHJ";
$conn = new mysqli($servername, $dbUser, $dbPassword, $dbname);

if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// Step 1: 查詢每間學校有多少人選
$schools = [];
$sql = "SELECT p.school_id, s.school_name, COUNT(p.user) AS student_count
        FROM Preferences p
        JOIN school s ON p.school_id = s.school_id
        GROUP BY p.school_id, s.school_name";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $schools[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>學生選擇統計</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>學生志願選擇統計</h1>
    
    <!-- Step 1: 學校選擇圖表 -->
    <canvas id="schoolChart"></canvas>

    <!-- Step 2: 顯示科系人數 -->
    <div id="departmentsContainer" style="display:none;">
        <h2>科系選擇</h2>
        <canvas id="departmentChart"></canvas>
    </div>

    <!-- Step 3: 顯示學生名單 -->
    <div id="studentsContainer" style="display:none;">
        <h2>學生名單</h2>
        <ul id="studentList"></ul>
    </div>

    <script>
        $(document).ready(function () {
            // Step 1: 顯示學校統計圖表
            const schoolData = <?php echo json_encode($schools); ?>;
            const schoolLabels = schoolData.map(data => data.school_name);
            const schoolCounts = schoolData.map(data => data.student_count);

            const schoolChart = new Chart(document.getElementById('schoolChart'), {
                type: 'bar',
                data: {
                    labels: schoolLabels,
                    datasets: [{
                        label: '選擇人數',
                        data: schoolCounts,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    onClick: function (event, elements) {
                        if (elements.length > 0) {
                            const index = elements[0].index;
                            const schoolId = schoolData[index].school_id;
                            loadDepartments(schoolId);
                        }
                    }
                }
            });

            // Step 2: 加載科系資料
            function loadDepartments(schoolId) {
                $.get('departments.php', { school_id: schoolId }, function (data) {
                    const departmentData = JSON.parse(data);
                    const departmentLabels = departmentData.map(d => d.department_name);
                    const departmentCounts = departmentData.map(d => d.student_count);

                    $('#departmentsContainer').show();
                    const departmentChart = new Chart(document.getElementById('departmentChart'), {
                        type: 'bar',
                        data: {
                            labels: departmentLabels,
                            datasets: [{
                                label: '選擇人數',
                                data: departmentCounts,
                                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            onClick: function (event, elements) {
                                if (elements.length > 0) {
                                    const index = elements[0].index;
                                    const departmentId = departmentData[index].department_id;
                                    loadStudents(departmentId);
                                }
                            }
                        }
                    });
                });
            }

            // Step 3: 加載學生名單
            function loadStudents(departmentId) {
                $.get('students.php', { department_id: departmentId }, function (data) {
                    const students = JSON.parse(data);
                    $('#studentsContainer').show();
                    $('#studentList').empty();
                    students.forEach(student => {
                        $('#studentList').append(`<li>${student.student_name}</li>`);
                    });
                });
            }
        });
    </script>
</body>
</html>
