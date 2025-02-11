<?php
session_start();
$user_id = $_SESSION['user_id'] ?? null; // 確保有使用者 ID

// 連接資料庫
$link = mysqli_connect("127.0.0.1", "HCHJ", "xx435kKHq", "HCHJ");
if (!$link) {
    die("資料庫連接失敗: " . mysqli_connect_error());
}

$class_list = [];

if ($user_id) {
  $stmt = mysqli_prepare($link, "SELECT grade, class FROM user_classes WHERE user_id = ?");
  mysqli_stmt_bind_param($stmt, "s", $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  while ($row = mysqli_fetch_assoc($result)) {
      $class_list[] = $row['grade'] . $row['class'];
  }
  mysqli_stmt_close($stmt);
}


// 關閉資料庫連線
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>班級選擇</title>
    <style>
        .class-buttons { display: flex; gap: 10px; }
        .class-buttons button { background-color: green; color: white; padding: 10px; border: none; cursor: pointer; font-weight: bold; }
        .hidden { display: none; }
    </style>
</head>
<body>

    <h2>帶班班級名單</h2>

    <!-- 班級選擇按鈕 -->
    <div class="class-buttons">
        <?php foreach ($class_list as $class): ?>
            <button id="btn-<?php echo $class; ?>" onclick="showFunctions('<?php echo $class; ?>')"><?php echo $class; ?></button>
        <?php endforeach; ?>
    </div>

    <!-- 功能按鈕區 -->
    <div id="functions-container">
        <?php foreach ($class_list as $class): ?>
            <div id="functions-<?php echo $class; ?>" class="hidden">
                <h3><?php echo $class; ?> 功能選擇</h3>
                <form action="index-02.php" method="POST">
                    <input type="hidden" name="grade" value="<?php echo substr($class, 0, -1); ?>">
                    <input type="hidden" name="class" value="<?php echo substr($class, -1); ?>">
                    <button type="submit">查看備審</button>
                </form>
                <form action="viewapplicationorder-02.php" method="POST">
                    <input type="hidden" name="grade" value="<?php echo substr($class, 0, -1); ?>">
                    <input type="hidden" name="class" value="<?php echo substr($class, -1); ?>">
                    <button type="submit">查看志願</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function showFunctions(selectedClass) {
            // 如果使用者只有一個班級，不隱藏其他按鈕
            const classButtons = document.querySelectorAll(".class-buttons button");
            if (classButtons.length > 1) {
                // 隱藏所有功能區塊
                document.querySelectorAll("[id^='functions-']").forEach(div => {
                    div.classList.add("hidden");
                });

                // 顯示選擇的班級對應的功能按鈕
                document.getElementById("functions-" + selectedClass).classList.remove("hidden");
            }
        }

        // 頁面加載後自動檢查是否有多個班級
        window.onload = function() {
            const classButtons = document.querySelectorAll(".class-buttons button");
            if (classButtons.length === 1) {
                // 只有一個班級時，直接顯示該班級的功能按鈕
                showFunctions(classButtons[0].innerText);
            }
        };
    </script>

</body>
</html>
