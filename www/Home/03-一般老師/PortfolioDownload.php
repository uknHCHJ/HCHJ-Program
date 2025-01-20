<?php
if (isset($_GET['id'])) {
    // 資料庫設定
    $servername = "127.0.0.1";
    $username = "HCHJ";
    $password = "xx435kKHq";
    $dbname = "HCHJ";

    // 建立連線
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("連線失敗：" . $conn->connect_error);
    }

    $id = intval($_GET['id']);

    // 查詢檔案資料
    $sql = "SELECT file_name, file_path FROM portfolio WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $file_name = $row['file_name'];
        $file_path = $row['file_path'];

        if (file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo "檔案不存在！";
        }
    } else {
        echo "檔案未找到！";
    }

    $stmt->close();
    $conn->close();
}
?>
