<?php
session_start();
include 'includes/db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Trang quản trị - Nhà thuốc</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <style>
        body {
            margin: 0;
            font-family: 'Times new roman';
            background-color: #f4f6f9;
            display: flex;
            height: 100vh;
            color: #333;
        }

        /* Sidebar cơ bản */
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #007ACC, #00695C);
            color: white;
            padding: 25px 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 6px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease, padding 0.3s ease;
            overflow: hidden;
        }

        /* Thu nhỏ sidebar */
        .sidebar.collapsed {
            width: 60px;
            padding: 25px 8px;
        }

        /* Tiêu đề */
        .sidebar h2 {
            margin-top: 0;
            text-align: center;
            font-size: 20px;
            margin-bottom: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            user-select: none;
            transition: opacity 0.3s ease;
        }

        /* Ẩn tiêu đề khi collapsed */
        .sidebar.collapsed h2 {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Chữ chào mừng */
        .sidebar p {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 30px;
            color: #d0e8f2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            user-select: none;
            transition: opacity 0.3s ease;
        }

        /* Ẩn chữ chào khi collapsed */
        .sidebar.collapsed p {
            opacity: 0;
            height: 0;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Menu link */
        .sidebar a {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 15px 16px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            user-select: none;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Icon trong menu */
        .sidebar a .icon {
            margin-right: 12px;
            font-size: 18px;
            min-width: 22px;
            text-align: center;
            transition: margin 0.3s ease;
        }

        /* Khi collapsed, ẩn chữ, chỉ hiện icon */
        .sidebar.collapsed a {
            justify-content: center;
            padding: 12px 0;
        }

        .sidebar.collapsed a .text {
            display: none;
        }

        /* Active & hover */
        .sidebar a:hover,
        .sidebar a.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff;
            box-shadow: none;
        }

        /* Logout */
        .logout {
            margin-top: 15px;
            text-align: center;
            transition: opacity 0.3s ease;
        }

        .logout a {
            color: #ffeaea;
            text-decoration: underline;
            font-size: 14px;
            user-select: none;
            display: inline-block;
            white-space: nowrap;
            transition: opacity 0.3s ease;
        }

        /* Ẩn chữ logout khi collapsed */
        .sidebar.collapsed .logout a {
            font-size: 0;
            text-decoration: none;
            pointer-events: none;
            opacity: 0;
        }

        /* Nội dung chính */
        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #e9f0f7;
        }

        iframe {
            width: 100%;
            height: calc(100vh - 40px);
            border: 2px solid #ccc;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background: white;
        }
    </style>

    <script>
        function loadPage(page, element) {
            const links = document.querySelectorAll(".sidebar a");
            links.forEach(link => link.classList.remove("active"));
            element.classList.add("active");

            document.getElementById("main-frame").src = page;

            // Thu nhỏ sidebar khi click menu
            const sidebar = document.querySelector(".sidebar");
            sidebar.classList.add("collapsed");
        }

        window.onload = function() {
            const sidebar = document.querySelector(".sidebar");

            // Mặc định chọn trang đầu tiên
            document.querySelector(".sidebar a.default").click();

            sidebar.addEventListener("mouseenter", () => {
                sidebar.classList.remove("collapsed");
            });

            sidebar.addEventListener("mouseleave", () => {
                sidebar.classList.add("collapsed");
            });
        };
    </script>
</head>

<body>
    <div class="sidebar collapsed">
        <h2>Quản trị</h2>
        <p> Xin chào <strong><?= htmlspecialchars($username) ?></strong></p>

        <a href="bctk.php" class="default" onclick="loadPage('bctk.php', this); return false;">
            <span class="icon">📊</span><span class="text">Báo cáo thống kê</span>
        </a>
        <a href="qlnd.php" onclick="loadPage('qlnd.php', this); return false;">
            <span class="icon">👥</span><span class="text">Quản lý người dùng</span>
        </a>
        <a href="qldp.php" onclick="loadPage('qldp.php', this); return false;">
            <span class="icon">💊</span><span class="text">Quản lý dược phẩm</span>
        </a>
        <a href="qldh.php" onclick="loadPage('qldh.php', this); return false;">
            <span class="icon">📦</span><span class="text">Quản lý đơn hàng</span>
        </a>


        <div class="logout">
            <a href="index.php">← Thoát</a>
        </div>
    </div>

    <div class="main-content">
        <iframe id="main-frame" src=""></iframe>
    </div>
</body>

</html>