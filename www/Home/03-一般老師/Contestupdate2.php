<?php 
$servername = "127.0.0.1";  
$username = "HCHJ";  
$password = "xx435kKHq";  
$dbname = "HCHJ";  

// 建立資料庫連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 檢查連線
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 確認是否有傳入比賽ID
if (isset($_GET['ID']) && !empty($_GET['ID'])) {
    $id = intval($_GET['ID']); // 確保ID是整數

    // 檢查表單是否已提交
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['name']) && !empty($_POST['link'])) {
            $name = trim($_POST['name']);
            $link = trim($_POST['link']);

            // 準備更新資料庫的SQL語句
            $sql = "UPDATE information SET name = ?, link = ? WHERE ID = ?";

            // 使用預備語句來避免SQL注入
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssi", $name, $link, $id);

                // 執行更新操作
                if ($stmt->execute()) {
                    echo "比賽資訊更新成功!";
                    header("location:Contestupdate1.php?ID=" . $id);
                    exit; // 停止腳本執行
                } else {
                    die("比賽資訊更新失敗：" . $stmt->error);
                }

                // 釋放語句
                $stmt->close();
            } else {
                die("準備語句失敗：" . $conn->error);
            }
        } else {
            die("請填寫完整的比賽資訊。");
        }
    }
} else {
    die("未指定比賽ID");
}

// 關閉資料庫連線
$conn->close();
?>