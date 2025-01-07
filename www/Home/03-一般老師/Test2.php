<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二技落點分析</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">二技落點分析系統</h2>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="score" class="form-label">統測分數：</label>
                <input type="number" class="form-control" id="score" name="score" placeholder="輸入您的總分" required>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">地區偏好：</label>
                <select class="form-select" id="location" name="location">
                    <option value="不限">不限</option>
                    <option value="北部">北部</option>
                    <option value="中部">中部</option>
                    <option value="南部">南部</option>
                    <option value="東部">東部</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">分析</button>
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 接收表單數據
            $score = $_POST['score'];
            $location = $_POST['location'];

            // 連接資料庫
            $conn = new mysqli('127.0.0.1', 'HCHJ', 'xx435kKHq', 'HCHJ');
       
            if ($conn->connect_error) {
                die("連接失敗：" . $conn->connect_error);
            }

            // 查詢符合條件的學校
            $sql = "SELECT school_name, major, min_score, location FROM University WHERE min_score <= ?";
            $params = [$score];
            if ($location !== '不限') {
                $sql .= " AND location = ?";
                $params[] = $location;
            }

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo '<h3 class="mt-4">推薦學校：</h3>';
                echo '<div class="list-group mt-3">';

                while ($row = $result->fetch_assoc()) {
                    $probability = round(($score / $row['min_score']) * 100);
                    if ($probability > 100) $probability = 100;

                    echo '<div class="list-group-item">';
                    echo '<h5>' . $row['school_name'] . ' (' . $row['major'] . ')</h5>';
                    echo '<p>地區：' . $row['location'] . '</p>';
                    echo '<div class="progress">';
                    echo '<div class="progress-bar" role="progressbar" style="width: ' . $probability . '%;" aria-valuenow="' . $probability . '" aria-valuemin="0" aria-valuemax="100">' . $probability . '%</div>';
                    echo '</div>';
                    echo '</div>';
                }

                echo '</div>';
            } else {
                echo '<p class="mt-4 text-danger">沒有符合條件的學校。</p>';
            }

            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
