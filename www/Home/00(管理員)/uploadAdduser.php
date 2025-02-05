<?php
session_start();
include 'db.php';
require 'vendor/autoload.php'; // 引入 PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["excel_file"])) {
    $file = $_FILES["excel_file"]["tmp_name"];

    if (!$file) {
        echo "<script>alert('請上傳檔案！'); window.history.back();</script>";
        exit();
    }

    try {
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        // 確保資料行數大於 1（第一行通常是標題）
        if (count($rows) <= 1) {
            echo "<script>alert('Excel 檔案沒有可匯入的資料！'); window.history.back();</script>";
            exit();
        }

        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            die("資料庫連接失敗：" . $conn->connect_error);
        }

        $inserted = 0;
        for ($i = 1; $i < count($rows); $i++) { // 跳過第一行標題
            list($user, $name, $department, $grade, $class, $permissions, $permissions2) = $rows[$i];

            // 避免空資料
            if (empty($user) || empty($name)) {
                continue;
            }

            // 檢查是否已經存在該帳號
            $checkQuery = "SELECT user FROM users WHERE user = ?";
            $stmt = $conn->prepare($checkQuery);
            $stmt->bind_param("s", $user);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 0) {
                // 插入資料
                $insertQuery = "INSERT INTO user (department, grade, class, name, user, password, Permissions) 
                                VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($insertQuery);
                $stmt->bind_param("sssssss", $department, $grade, $class, $name, $user, $password, $Permissions);
                $stmt->execute();
                $inserted++;
            }
        }

        echo "<script>alert('成功匯入 {$inserted} 筆資料！'); window.location.href='Adduser.php';</script>";
    } catch (Exception $e) {
        echo "<script>alert('Excel 讀取失敗：" . $e->getMessage() . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('無效的請求！'); window.history.back();</script>";
}
?>
