<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=HCHJ', 'HCHJ', 'xx435kKHq');
$statement = $pdo->prepare("SELECT school_id, school_name FROM School");
$statement->execute();
echo json_encode($statement->fetchAll(PDO::FETCH_ASSOC));
?>