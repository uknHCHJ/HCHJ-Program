<?php

$host = '127.0.0.1'; // 資料庫主機
$db = 'HCHJ'; // 資料庫名稱
$user = 'HCHJ'; // 使用者名稱
$pass = 'xx435kKHq'; // 密碼
try {
    // 建立資料庫連接
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 新增科系的資料
    $department_name = '新增科系名稱'; // 替換為您要新增的科系名稱

    // SQL 語句以新增科系
    $sql_department = "INSERT INTO Department (department_name) VALUES (:name)";
    
    // 準備和執行
    $stmt_department = $pdo->prepare($sql_department);
    $stmt_department->bindParam(':name', $department_name);
    
    if ($stmt_department->execute()) {
        $department_id = $pdo->lastInsertId(); // 獲取新插入科系的 ID
        
        // 學校 ID，替換為您要關聯的學校 ID
        $school_id = 1; 

        // SQL 語句以建立關聯
        $sql_relation = "INSERT INTO school_departments (school_id, department_id) VALUES (:school_id, :department_id)";
        
        // 準備和執行
        $stmt_relation = $pdo->prepare($sql_relation);
        $stmt_relation->bindParam(':school_id', $school_id);
        $stmt_relation->bindParam(':department_id', $department_id);
        
        if ($stmt_relation->execute()) {
            echo "科系新增並成功連接到學校！";
        } else {
            echo "新增關聯失敗！";
        }
    } else {
        echo "新增科系失敗！";
    }
} catch (PDOException $e) {
    echo "資料庫錯誤: " . $e->getMessage();
}

// 關閉連接
$pdo = null;
?>