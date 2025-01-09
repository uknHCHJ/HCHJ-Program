<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>備審資料管理</title>
</head>
<body>
    <h1>備審資料管理系統</h1>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="category">選擇資料類型：</label>
        <select name="category" id="category" required>
            <option value="成績單">成績單</option>
            <option value="自傳">自傳</option>
            <option value="學歷證明">學歷證明</option>
            <option value="競賽證明">競賽證明</option>
            <option value="實習證明">實習證明</option>
            <option value="相關證照">相關證照</option>
        </select>
        <br><br>
        <label for="file">上傳檔案：</label>
        <input type="file" name="file" id="file" required>
        <br><br>
        <button type="submit">上傳</button>
    </form>
    <hr>
    <h2>現有資料</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>類型</th>
                <th>檔案名稱</th>
                <th>上傳時間</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'db.php';
            $student_id = 1; // 假設目前使用者的學生 ID
            $sql = "SELECT * FROM portfolio WHERE student_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $student_id);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['category']}</td>
                    <td><a href='{$row['file_path']}' download>{$row['file_name']}</a></td>
                    <td>{$row['uploaded_at']}</td>
                    <td><form action='delete.php' method='post'>
                        <input type='hidden' name='id' value='{$row['id']}'>
                        <button type='submit' onclick='return confirm(\"確定要刪除這筆資料嗎？\")'>刪除</button>
                    </form></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
