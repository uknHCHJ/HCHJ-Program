<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>搜尋結果</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .search-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
            padding: 10px;
        }
        th {
            background-color: #f4f4f9;
        }
        tr:hover {
            background-color: #f9f9f9;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            color: white;
            cursor: pointer;
            font-size: 14px;
        }
        .btn-edit {
            background-color: #007BFF;
        }
        .btn-delete {
            background-color: #dc3545;
        }
        .no-results {
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <h1>搜尋結果</h1>
    <div class="search-container">
        <?php
        // PHP: 從資料庫獲取搜尋結果
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['query'])) {
            $query = trim($_GET['query']);
            $servername = "127.0.0.1";  
            $username = "HCHJ";  
            $password = "xx435kKHq";  
            $database = "HCHJ"; 

            $conn = mysqli_connect($servername, $username, $password, $database);

            if (!$conn) {
                echo '<p class="no-results">連線失敗，請稍後再試。</p>';
                exit;
            }

            $sql = "SELECT user, name, Permissions FROM user WHERE user LIKE ?";
            $stmt = mysqli_prepare($conn, $sql);
            $likeQuery = '%' . $query . '%';
            mysqli_stmt_bind_param($stmt, 's', $likeQuery);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                echo '<table>';
                echo '<tr><th>帳號</th><th>學號</th><th>操作</th></tr>';
                while ($row = mysqli_fetch_assoc($result)) {
                    $account = htmlspecialchars($row['user']);
                    $permission = htmlspecialchars($row['Permissions']);
                    $student = htmlspecialchars($row['name']);
                    echo "<tr>";
                    echo "<td>$account</td>";
                    echo "<td>$student</td>";
                    echo "<td>
                            <button class='btn btn-edit' onclick='editRow(\"$account\", \"$permission\")'>編輯</button>
                <button class='btn btn-delete' onclick='deleteRow(\"$account\")'>刪除</button>
                          </td>";
                    echo "</tr>";
                }
                echo '</table>';
            } else {
                echo '<p class="no-results">未找到相關資料。</p>';
            }

            mysqli_stmt_close($stmt);
            mysqli_close($conn);
        } else {
            echo '<p class="no-results">請輸入搜尋條件。</p>';
        }
        echo "<button onclick='history.back()'>返回上一頁</button>";
        ?>
    </div>

    <script>
      // 編輯功能：跳轉到編輯頁面
function editRow(account,permission) {
    // 跳轉到編輯頁面，將帳號作為參數傳遞
    window.location.href = 'Change-permissions1.php?username=' + encodeURIComponent(account)+ '&permission=' + encodeURIComponent(permission);
}

// 刪除功能：跳轉到刪除頁面
function deleteRow(account) {
    if (confirm("確定要刪除帳號：" + account + " 的資料嗎？")) {
        // 跳轉到刪除頁面，將帳號作為參數傳遞
        window.location.href = "delete-user.php" + encodeURIComponent(user);
    }
}

    </script>
</body>
</html>
