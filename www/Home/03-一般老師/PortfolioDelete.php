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

    // 刪除資料庫記錄
    $sql = "DELETE FROM portfolio WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "資料刪除成功！";
        header("Location:Portfolio1.php");

    } else {
        echo "資料刪除失敗：" . $stmt->error;
        header("Location:Portfolio1.php");
    }

    $stmt->close();
    $conn->close();
}
?>
