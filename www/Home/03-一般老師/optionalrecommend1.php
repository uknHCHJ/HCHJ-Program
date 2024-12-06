<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>統測成績填寫表單</title>
    <link rel="stylesheet" href="styles.css"> <!-- 可選的CSS樣式 -->
</head>
<body>
    <div class="container">
        <h1>填寫統測成績</h1>
        <form action="submit_scores.php" method="POST">
            <label for="user">學生帳號：</label>
            <input type="number" id="user" name="user" required><br><br>

            <label for="subject_name">科目:</label>
            <select id="subject_name" name="subject_name" required>
                <option value="數學">數學</option>
                <option value="英文">英文</option>
                <option value="國文">國文</option>
                <option value="自然">自然</option>
                <option value="社會">社會</option>
            </select><br><br>

            <label for="score">成績:</label>
            <input type="number" id="score" name="score" min="0" max="100" required><br><br>

            <button type="submit">提交成績</button>
        </form>
    </div>
</body>
</html>