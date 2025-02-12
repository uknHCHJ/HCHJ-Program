<?php
// 資料庫連線設定
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}

// 檢查是否要下載檔案
if (isset($_GET['download_id']) && !empty($_GET['download_id'])) {
    $fileId = intval($_GET['download_id']);

    // 查詢檔案資訊
    $sql = "SELECT file_name, file_path FROM portfolio WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("找不到檔案");
    }

    $row = $result->fetch_assoc();
    $filePath = $row['file_path'];
    $fileName = $row['file_name'];

    $stmt->close();
    $conn->close();

    // 確保檔案存在
    if (!file_exists($filePath)) {
        die("檔案不存在");
    }

    // 設定標頭，強制下載
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    readfile($filePath);
    exit;
}

// 查詢該學生的資料
$sql = "SELECT * FROM portfolio WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>備審資料管理系統</title>
</head>
<body>
    <div style="text-align: center; margin: auto;">
        <h1>備審資料管理系統</h1>
        <form action="PortfolioCreat.php" method="post" enctype="multipart/form-data" id="uploadForm" onsubmit="return confirmUpload()">
            <input type="hidden" name="student_id" value="<?php echo htmlspecialchars($userId, ENT_QUOTES, 'UTF-8'); ?>">
            
            <label for="category">選擇資料類型：</label>
            <select name="category" id="category" required>
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

            <label for="file">上傳檔案：</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">上傳</button>
        </form>
    </div>

    <div class="portfolio-section pt-130">
        <div id="container" class="container">
            <div class="row">
                <div class="col-12">
                    <div class="portfolio-btn-wrapper">
                        <button type="button" class="portfolio-btn active" data-filter="*">全部</button>
                        <button type="button" class="portfolio-btn" data-filter=".transcripts">成績單</button>
                        <button type="button" class="portfolio-btn" data-filter=".autobiographies">自傳</button>
                        <button type="button" class="portfolio-btn" data-filter=".certificates">學歷證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".competitions">競賽證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".internships">實習證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".licenses">相關證照</button>
                        <button type="button" class="portfolio-btn" data-filter=".language-skills">語言能力證明</button>
                        <button type="button" class="portfolio-btn" data-filter=".Topics">專題資料</button>
                        <button type="button" class="portfolio-btn" data-filter=".reading-plan">讀書計畫</button>
                        <button type="button" class="portfolio-btn" data-filter=".Other-information">其他資料</button>
                    </div>
                    <div class="row grid">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<div class='col-lg-4 col-md-6 portfolio-item'>
                                    <div class='portfolio-content'>
                                        <h3>{$row['category']}</h3>
                                        <p><a href='?download_id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' download>{$row['file_name']}</a></p>
                                        <p>上傳時間：{$row['upload_time']}</p>
                                        <form action='PortfolioDelete.php' method='post'>
                                            <input type='hidden' name='id' value='" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "'>
                                            <button type='submit' onclick='return confirm(\"確定要刪除這筆資料嗎？\")'>刪除</button>
                                        </form>
                                    </div>
                                </div>";
                            }
                        } else {
                            echo "<div class='col-12'><p>尚無資料</p></div>";
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmUpload() {
            const fileInput = document.getElementById('file');
            const file = fileInput.files[0];

            if (!file) {
                alert('請選擇一個檔案來上傳');
                return false;
            }

            return confirm(`您確定要上傳檔案：${file.name}？`);
        }
    </script>
</body>
</html>