<?php
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連接失敗: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $grade = $_POST['grade'];
    $class = $_POST['class'];
    $student_id = $_POST['student_id'];
    $category = $_POST['category'];
    $sub_category = isset($_POST['sub_category']) ? $_POST['sub_category'] : '';
    
    if (!empty($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_content = file_get_contents($file_tmp);
        $file_content = addslashes($file_content);
        
        $final_category = ($category == "相關證照") ? $category . " - " . $sub_category : $category;
        
        $sql = "INSERT INTO portfolio (grade, class, student_id, category, file_name, file_content) 
                VALUES ('$grade', '$class', '$student_id', '$final_category', '$file_name', '$file_content')";
        
        if ($conn->query($sql) === TRUE) {
            echo "檔案上傳成功！";
        } else {
            echo "錯誤: " . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>學生備審資料上傳</title>
    <script>
        function toggleSubCategory() {
            var category = document.getElementById("category").value;
            var subCategoryDiv = document.getElementById("sub_category_div");
            
            if (category === "相關證照") {
                subCategoryDiv.style.display = "block";
            } else {
                subCategoryDiv.style.display = "none";
            }
        }
    </script>
</head>
<body>
    <h2>學生備審資料上傳</h2>
    <form action="" method="post" enctype="multipart/form-data">
        年級: <input type="text" name="grade" required><br>
        班級: <input type="text" name="class" required><br>
        學號: <input type="number" name="student_id" required><br>
        資料分類:
        <select name="category" id="category" onchange="toggleSubCategory()" required>
            <option value="成績單">成績單</option>
            <option value="自傳">自傳</option>
            <option value="學歷證明">學歷證明</option>
            <option value="競賽證明">競賽證明</option>
            <option value="實習證明">實習證明</option>
            <option value="相關證照">相關技術證照</option>
            <option value="語言能力證明">語言能力證明</option>
            <option value="專題資料">專題資料</option>
            <option value="讀書計畫">讀書計畫</option>
            <option value="其他資料">其他資料</option>
        </select><br>
        
        <div id="sub_category_div" style="display: none;">
            相關技術證照分類:
            <select name="sub_category">
                <option value="ACM - CPE">ACM - CPE大學程式能力檢定</option>
                <option value="Adobe">Adobe</option>
                <option value="GLAD">GLAD</option>
                <option value="Microsoft">Microsoft</option>
                <option value="MOCC">MOCC - 中華民國電腦教育發展協會</option>
                <option value="勞動部勞動力發展署">勞動部勞動力發展署</option>
                <option value="台灣醫學資訊協會">台灣醫學資訊協會 - 醫療資訊管理師</option>
                <option value="TOEIC">多益(TOEIC) - 美國教育測驗服務社(ETS)</option>
                <option value="CPR">臺灣急救教育推廣與諮詢中心 - 心肺復甦術(CPR)</option>
                <option value="TQC">TQC - 財團法人中華民國電腦技能基金會</option>
            </select>
        </div>
        
        選擇檔案: <input type="file" name="file" required><br>
        <button type="submit">上傳</button>
    </form>
</body>
</html>
