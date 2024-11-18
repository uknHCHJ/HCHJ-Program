<?php
// 連接資料庫
$servername = "127.0.0.1";
$username = "HCHJ";
$password = "xx435kKHq";
$dbname = "HCHJ";

// 建立連線
$conn = new mysqli($servername, $username, $password, $dbname);

// 確認連線是否成功
if ($conn->connect_error) {
    die("連線失敗: " . $conn->connect_error);
}

// 從資料庫中查詢所有科系
$sql = "SELECT ID, department_name FROM department";
$result = $conn->query($sql);

// 檢查是否有查詢結果
if ($result->num_rows > 0) {
    // 生成下拉選單
    echo '<form method="post" action="submit_choices.php">';
    echo '<label for="department">選擇科系：</label>';
    echo '<select name="department" class="form-select mb-3" id="department">';
    echo '<option value="">請選擇科系</option>';  // 預設選項

    // 遍歷資料，並將每個科系作為選項
    while($row = $result->fetch_assoc()) {
        echo '<option value="' . $row['ID'] . '">' . $row['department_name'] . '</option>';
    }

    echo '</select>';
    echo '<button type="submit" class="btn btn-primary">送出</button>';
    echo '</form>';
} else {
    echo "無可用的科系資料";
}

// 關閉資料庫連線
$conn->close();
?>
