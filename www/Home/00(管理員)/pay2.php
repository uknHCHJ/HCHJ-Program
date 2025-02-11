<?php
header("Content-Type: application/json; charset=UTF-8");

$conn = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$conn) {
    die(json_encode(["error" => "資料庫連接失敗"]));
}

$sql = "SELECT id, name, file_path FROM your_table";
$result = mysqli_query($conn, $sql);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $row["status"] = !empty($row["file_path"]) ? "✔️" : "❌";
    $data[] = $row;
}

mysqli_close($conn);
echo json_encode($data);
?>
