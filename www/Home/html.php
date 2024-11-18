<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>左側選單頁籤</title>
    <style>
        /* 重設樣式 */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* 設置側邊欄背景色 */
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #007bff;
            color: white;
            position: fixed;
            left: 0;
            top: 0;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            padding: 10px;
            font-size: 20px;
            color: #fff;
        }

        /* 下拉選單樣式 */
        .sub-menu-bar ul {
            list-style: none;
            padding-left: 0;
        }

        .sub-menu-bar .nav-item {
            padding: 10px 20px;
        }

        .sub-menu-bar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            display: block;
            cursor: pointer;
        }

        .sub-menu-bar a:hover {
            background-color: #0056b3;
        }

        /* 子選單樣式 */
        .sub-menu {
            display: none;
            padding-left: 15px;
        }

        .nav-item:hover .sub-menu {
            display: block;
        }

        /* 右側主內容區域 */
        .content {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>選單</h2>
        <div class="collapse navbar-collapse sub-menu-bar" id="navbarSupportedContent">
            <ul id="nav" class="navbar-nav">
                <li class="nav-item"><a href="index-03.php">首頁</a></li>
                <li class="nav-item">
                    <a class="nav-item dd-menu">個人資料</a>
                    <ul class="sub-menu">
                        <li class="nav-item"><a href="contact-03(個人資料).php">查看個人資料</a></li>
                        <li class="nav-item"><a href="changepassword-01.html(修改密碼)">修改密碼</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-item dd-menu">二技校園網</a>
                    <ul class="sub-menu">
                        <li class="nav-item"><a href="Schoolnetwork1.php">首頁</a></li>
                        <li class="nav-item"><a href="pointsgo.php">加分攻略</a></li>
                        <li class="nav-item"><a href="AddSchool1.php">新增校園</a></li>
                        <li class="nav-item"><a href="portfolio delete-03(編輯).php">編輯資訊</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-item dd-menu">比賽資訊</a>
                    <ul class="sub-menu">
                        <li class="nav-item"><a href="blog-03(競賽).php">查看</a></li>
                        <li class="nav-item"><a href="create-03.php">新增</a></li>
                        <li class="nav-item"><a href="delete-03.php">編輯</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="page-scroll">目前登入使用者：<?php echo $userId; ?></a>
                </li>
                <li class="nav-item">
                    <a class="page-scroll" href="/~HCHJ/Permission.php">切換使用者</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="content">
        <h1>主內容區域</h1>
        <p>這是主頁面內容。</p>
    </div>
</body>
</html>