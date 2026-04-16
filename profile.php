<?php
session_start();
include 'includes/db.php'; // Kết nối cơ sở dữ liệu

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Lấy thông tin người dùng từ cơ sở dữ liệu
$sql = "SELECT username, avatar, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $avatar = $user['avatar']; // Giữ biến để tránh lỗi, nhưng không sử dụng
    $email = htmlspecialchars($user['email'] ?? 'Chưa cập nhật');
    $role = htmlspecialchars($user['role']);
} else {
    $username = 'Người dùng';
    $avatar = ''; // Không đặt default-avatar.jpg
    $email = 'Chưa cập nhật';
    $role = 'user';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin cá nhân - Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <style>
       *{
        padding:0;
        magin:0;
        box-sizing:border-box;
    }
        .profile-container {
            display: flex;
            margin: 20px 100px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .sidebar {
            width: 200px;
            background: #e9ecef;
            padding: 20px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #007bff;
            color: white;
        }
        .profile-content {
            flex-grow: 1;
            text-align: center;
            padding: 10px;
        }
        .profile-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 10px;
            margin-bottom: 20px;
        }
        .profile-info div {
            padding: 10px;
        }
        .edit-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        .edit-btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <section class="profile-container">
        <div class="sidebar">
            <a href="profile.php" align="center">Thông tin cá nhân</a>
            <a href="orders.php">Đơn hàng đã đặt</a>
            <a href="javascript:void(0)" onclick="showDevelopmentMessage()">Theo dõi đơn hàng</a>
        </div>
        <div class="profile-content">
                <h2>Thông tin cá nhân</h2>
            <div class="profile-info">
                <div><strong>Tên người dùng</strong></div><div><?php echo $username; ?></div>
                <div><strong>Email</strong></div><div><?php echo $email; ?></div>
                <div><strong>Vai trò</strong></div><div><?php echo $role; ?></div>
            </div>
            <a href="edit_profile.php" class="edit-btn">Chỉnh sửa thông tin</a>
        </div>
    </section>
    <?php include 'includes/footer.php' ?>
    <script>
    function showDevelopmentMessage() {
        alert("Chức năng đang được phát triển!");
    }
</script> 
</body>
</html>