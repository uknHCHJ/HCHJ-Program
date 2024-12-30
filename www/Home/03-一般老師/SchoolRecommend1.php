<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>成績輸入</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        label {
            display: inline-block;
            width: 100px;
            margin-bottom: 10px;
        }
        input[type="number"] {
            width: 50px;
            padding: 5px;
            margin-bottom: 10px;
        }
        input[type="submit"] {
            padding: 5px 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <h1>請輸入您的成績</h1>
    <form action="SchoolRecommend2.php" method="post">
        <label for="chinese">國文：</label>
        <input type="number" id="chinese" name="chinese" min="0" max="100" required><br>

        <label for="english">英文：</label>
        <input type="number" id="english" name="english" min="0" max="100" required><br>

        <label for="math">數學：</label>
        <input type="number" id="math" name="math" min="0" max="100" required><br>

        <label for="professional">專業科目：</label>
        <input type="number" id="professional" name="professional" min="0" max="100" required><br>

        <input type="submit" value="提交">
    </form>
</body>
</html>