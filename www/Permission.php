<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user'])) {
    echo("<script>
          alert('請先登入！！');
          window.location.href = '/~HCHJ/index.html'; 
          </script>");
    exit();
}

$userData = $_SESSION['user']; //

// 在SESSION 中儲存了唯一識別符（例如 user_id 或 username）
$userId = $userData['user']; // 從 SESSION 中獲取 user_id
$username=$userData['name']; 
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <title>選擇登入權限</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" media="all">
    <link rel="shortcut icon" type="image/x-icon" href="Home/schoolimages/ukn.png">
    <style>
        /* 自定義 radiobox 樣式 */
        #roles-container {
            display: flex;
            flex-direction: column;
        }
        
        .radio-box {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .radio-box input[type="radio"] {
            display: none; /* 隱藏默認的 radio button */
        }

        .radio-box label {
            position: relative;
            padding-left: 30px; /* 留出空間給自定義的選擇框 */
            cursor: pointer;
            font-size: 16px; /* 調整字體大小 */
            user-select: none; /* 禁用文本選擇 */
        }

        .radio-box label::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 20px; /* 自定義選擇框寬度 */
            height: 20px; /* 自定義選擇框高度 */
            border: 2px solid #007BFF; /* 自定義邊框顏色 */
            border-radius: 50%; /* 使其為圓形 */
            background-color: white; /* 自定義背景顏色 */
        }

        .radio-box input[type="radio"]:checked + label::before {
            background-color: #007BFF; /* 選中時的背景顏色 */
            border-color: #0056b3; /* 選中時的邊框顏色 */
        }
    </style>
</head>
<body>
    <section class="w3l-hotair-form">
        <h1>升學競賽全方位資源網</h1>
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-hotair">
                    <div class="content-wthree">
                        <h2>選擇權限</h2>
                        <form id="login-form">
                            <div id="roles-container">
                                <p>請選擇登入身份：</p>
                            </div>
                            
                            <button class="button" type="submit">進入系統</button>
                        </form> 
                        <p style="font-size: 14px; font-family: Arial, sans-serif; font-weight: bold;">
                            康寧大學(台北校區)-資訊管理科
                        </p>                       
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/1.png" alt="" class="img-fluid">
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </section>

    <script>
        // 權限 ID 對應顯示文字
        const roleNames = {
            "0": "管理員",
            "1":"學生",
            "3": "一般老師",
            "2": "導師",
            "4": "最高行政人員",
            // 可添加更多權限對應
        };

        // 從後端獲取權限 ID
        fetch('get_roles.php')
            .then(function(response) { return response.json(); })
            .then(function(data) {
                var rolesContainer = document.getElementById('roles-container');
                for (var i = 0; i < data.length; i++) {
                    var roleId = data[i];
                    var roleName = roleNames[roleId] || "沒有其他權限";

                    // 創建 radio box
                    var radioBox = document.createElement('div');
                    radioBox.className = 'radio-box';

                    var radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = 'role';  // 單選
                    radio.value = roleId; 
                    radio.id = 'role-' + roleId;
                    
                    // 創建標籤顯示對應的權限名稱
                    var label = document.createElement('label');
                    label.htmlFor = 'role-' + roleId;
                    label.textContent = roleName;  // 顯示對應文字
                    
                    radioBox.appendChild(radio);
                    radioBox.appendChild(label);
                    rolesContainer.appendChild(radioBox);
                }
            })
            .catch(function(error) { console.error('錯誤:', error); });

        // 提交表單並根據選擇的權限進行跳轉
        document.getElementById('login-form').onsubmit = function(event) {
            event.preventDefault();  // 阻止默認的表單提交

            var selectedRole = document.querySelector('input[name="role"]:checked');
            if (selectedRole) {
                var roleId = selectedRole.value;
                
                // 根據選擇的角色 ID 進行跳轉
                var redirectUrl;
                switch (roleId) {
                    case "0":
                        redirectUrl = "/~HCHJ/Home/index-00.php"; // 管理員頁面
                        break;
                    case "1":
                        redirectUrl = "/~HCHJ/Home/index-01.php"; // 學生頁面
                        break;
                    case "2":
                        redirectUrl = "/~HCHJ/Home/index-02.php"; // 導師頁面
                        break;
                    case "3":
                        redirectUrl = "/~HCHJ/Home/index-03.php"; // 老師頁面
                        break;
                    case "4":
                        redirectUrl = "/~HCHJ/Home/index-04.php"; // 最高行政人員頁面
                        break;
                    default:
                        redirectUrl = "/~HCHJ/index.html";// 默認頁面
                        alert('重新登入！');
                         
                }
                
                window.location.href = redirectUrl; // 跳轉至指定頁面
            } else {
                alert('請選擇登入身份！'); // 提示用戶選擇身份
            }
        };
    </script>
</body>
</html>
