<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>二技錄取評估系統</title>
</head>
<body>
    <h1>二技錄取評估系統</h1>
    <form action="Test2.php" method="POST">
        <label for="chinese">國文成績：</label>
        <input type="number" step="0.01" name="chinese" id="chinese" required><br><br>
        
        <label for="english">英文成績：</label>
        <input type="number" step="0.01" name="english" id="english" required><br><br>
        
        <label for="math">數學成績：</label>
        <input type="number" step="0.01" name="math" id="math" required><br><br>
        
        <label for="professional">專業科目成績：</label>
        <input type="number" step="0.01" name="professional" id="professional" required><br><br>
        
        <button type="submit">提交成績</button>
    </form>
</body>
</html>