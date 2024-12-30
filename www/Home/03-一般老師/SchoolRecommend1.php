<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>學生分數輸入</title>
</head>
<body>
    <h1>輸入統測分數</h1>
    <form action="SchoolRecommend2.php" method="POST">
        <label>國文分數: </label>
        <input type="number" name="chinese_score" required><br>
        
        <label>英文分數: </label>
        <input type="number" name="english_score" required><br>
        
        <label>數學分數: </label>
        <input type="number" name="math_score" required><br>
        
        <label>專業科目分數: </label>
        <input type="number" name="professional_score" required><br>
        
        <button type="submit">提交</button>
    </form>
</body>
</html>