<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

if (!isset($_SESSION['user'])) {
    echo("<script>
        alert('請先登入！！');
        window.location.href = '/~HCHJ/index.html';
    </script>");
    exit();
}

$userData = $_SESSION['user'];
$userId = $userData['user'];
$username = $userData['name'];

$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("❌ 資料庫連接失敗: " . mysqli_connect_error());
}
mysqli_query($link, 'SET NAMES UTF8');

$school_id = $_POST['school_id'];
$dept_id = $_POST['dept_id'];
$skilled_num = $_POST['skilled_num'];
$application_num = $_POST['Application_num'];

if (!is_numeric($skilled_num) || !is_numeric($application_num) || $skilled_num < 0 || $application_num < 0) {
    die("❌ 請輸入有效的正整數名額！");
}

// 1. 先檢查資料是否存在
$check_sql = "SELECT * FROM test WHERE school_id = ? AND department_id = ?";
$check_stmt = mysqli_prepare($link, $check_sql);
mysqli_stmt_bind_param($check_stmt, "ii", $school_id, $dept_id);
mysqli_stmt_execute($check_stmt);
$result = mysqli_stmt_get_result($check_stmt);

if (mysqli_num_rows($result) > 0) {
    // 資料已存在，執行 UPDATE
    $update_sql = "UPDATE test SET skilled_num = ?, Application_num = ? WHERE school_id = ? AND department_id = ?";
    $update_stmt = mysqli_prepare($link, $update_sql);
    mysqli_stmt_bind_param($update_stmt, "iiii", $skilled_num, $application_num, $school_id, $dept_id);
    $success = mysqli_stmt_execute($update_stmt);
    if ($success) {
        echo "✅ 名額更新成功！";
        echo "<script>window.location.href = 'new-school1.php';</script>";
    } else {
        echo "❌ 名額更新失敗：" . mysqli_error($link);
        echo "<script>window.location.href = 'new-school1.php';</script>";
    }
    mysqli_stmt_close($update_stmt);
} else {
    // 資料不存在，執行 INSERT
    $insert_sql = "INSERT INTO test (school_id, department_id, skilled_num, Application_num) VALUES (?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($link, $insert_sql);
    mysqli_stmt_bind_param($insert_stmt, "iiii", $school_id, $dept_id, $skilled_num, $application_num);
    $success = mysqli_stmt_execute($insert_stmt);
    if ($success) {
        echo "✅ 新增資料成功！";
        echo "<script>window.location.href = 'new-school1.php';</script>";
    } else {
        echo "❌ 新增資料失敗：" . mysqli_error($link);
        echo "<script>window.location.href = 'new-school1.php';</script>";
    }
    mysqli_stmt_close($insert_stmt);
}

mysqli_stmt_close($check_stmt);
mysqli_close($link);
?>
