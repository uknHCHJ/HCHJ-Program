<?php
$school_id = $_GET['school_id'];
$pdo = new PDO('mysql:host=127.0.0.1;dbname=HCHJ', 'HCHJ', 'xx435kKHq');
$statement = $pdo->prepare("SELECT department_id, department_name FROM Department WHERE school_id = ?");
$statement->execute([$school_id]);
echo json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
?>