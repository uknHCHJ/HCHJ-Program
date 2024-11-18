<?php
$pdo = new PDO('mysql:host=localhost;dbname=your_database', 'your_username', 'your_password');

// 取得學校和科系資料
$statement = $pdo->prepare("
    SELECT s.school_id, s.school_name, s.location, d.department_id, d.department_name
    FROM School s
    LEFT JOIN Department d ON s.school_id = d.school_id
    ORDER BY s.school_id, d.department_name
");
$statement->execute();
$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

$schools = [];
foreach ($rows as $row) {
    $school_id = $row['school_id'];
    if (!isset($schools[$school_id])) {
        $schools[$school_id] = [
            'school_id' => $row['school_id'],
            'school_name' => $row['school_name'],
            'location' => $row['location'],
            'departments' => []
        ];
    }
    if ($row['department_id']) {
        $schools[$school_id]['departments'][] = [
            'department_id' => $row['department_id'],
            'department_name' => $row['department_name']
        ];
    }
}

echo json_encode(array_values($schools));
?>