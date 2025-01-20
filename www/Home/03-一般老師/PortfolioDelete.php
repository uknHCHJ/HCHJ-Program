<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
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

    $id = intval($_POST['id']);

    // 查詢檔案路徑
    $sql = "SELECT file_path FROM portfolio WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $file_path = $row['file_path'];

        // 刪除檔案
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // 刪除資料庫記錄
        $sql = "DELETE FROM portfolio WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo "資料刪除成功！";
        } else {
            echo "資料刪除失敗：" . $stmt->error;
        }
    } else {
        echo "檔案未找到！";
    }

    $stmt->close();
    $conn->close();
}
?>
