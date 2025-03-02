<?php
$servername = "127.0.0.1";  // 或者 "localhost"
$username = "root";  // XAMPP 預設帳號是 root
$password = "";  // XAMPP 預設密碼是空的
$dbname = "Test";  // 你的資料庫名稱
// 建立資料庫連線

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 競賽公告網站
$url = "https://bhuntr.com/tw/competitions";

// 使用 cURL 獲取 HTML，避免 file_get_contents 被封鎖
function getHTML($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36");

    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

$html = getHTML($url);

// 解析 HTML
libxml_use_internal_errors(true); // 防止 HTML 解析錯誤
$dom = new DOMDocument();
$dom->loadHTML($html);
$xpath = new DOMXPath($dom);

// 根據實際 HTML 結構修改 XPath
$nodes = $xpath->query("//div[@class='competition']");

if ($nodes->length == 0) {
    die("❌ 未找到任何競賽資料，請確認 XPath 是否正確！");
}

// 插入資料庫
foreach ($nodes as $node) {
    $title = trim($node->getElementsByTagName("h2")->item(0)->nodeValue);
    $date = trim($node->getElementsByTagName("span")->item(0)->nodeValue);

    // 輸出 HTML 結構（用來 Debug，確保 XPath 正確）
    echo "==== 找到一個競賽區塊 ====\n";
    echo $node->C14N();
    echo "\n\n";

    // 儲存到 MySQL
    $stmt = $conn->prepare("INSERT INTO competitions (title, date) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $date);
    $stmt->execute();
}

echo "✅ 競賽資料已成功存入資料庫！";

// 關閉資料庫連線
$conn->close();
?>
