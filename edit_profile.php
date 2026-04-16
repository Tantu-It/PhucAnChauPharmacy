<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Lấy thông tin hiện tại
$sql = "SELECT username, avatar, email, password FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = htmlspecialchars($user['username']);
    $email = htmlspecialchars($user['email'] ?? '');
    $avatar = $user['avatar']; // Chỉ lấy giá trị từ db, không dùng default
    $stored_password = $user['password']; // Lấy mật khẩu đã mã hóa để so sánh
} else {
    $username = '';
    $email = '';
    $avatar = '';
    $stored_password = '';
}

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = $_POST['username'] ?? $username;
    $new_email = $_POST['email'] ?? $email;

    // Xử lý thay đổi mật khẩu nếu có
    if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Xác thực mật khẩu cũ
        if (password_verify($current_password, $stored_password)) {
            if ($new_password === $confirm_password && !empty($new_password)) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE users SET password = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $hashed_password, $user_id);

                if ($stmt->execute()) {
                    header("Location: profile.php?success=2"); // Thành công thay đổi mật khẩu
                    exit();
                } else {
                    $error = "Cập nhật mật khẩu thất bại!";
                }
            } else {
                $error = "Mật khẩu mới và xác nhận mật khẩu không khớp hoặc để trống!";
            }
        } else {
            $error = "Mật khẩu cũ không đúng!";
        }
    } else {
        // Cập nhật username và email
        $sql = "UPDATE users SET username = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $new_username, $new_email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Cập nhật phiên
            header("Location: profile.php?success=1"); // Thành công cập nhật thông tin
            exit();
        } else {
            $error = "Cập nhật thất bại!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin - Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <style>
        .edit-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .avatar-section {
            text-align: center;
            margin-bottom: 20px;
        }
        .avatar-section img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
        .edit-form div {
            margin-bottom: 15px;
        }
        .edit-form label {
            display: block;
            margin-bottom: 5px;
        }
        .edit-form input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .save-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
        .save-btn:hover {
            background: #0056b3;
        }
        .password-section {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <div class="edit-container">
        <h2>Chỉnh sửa thông tin cá nhân</h2>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] == 1) echo "<p style='color:green;'>Cập nhật thông tin thành công!</p>"; ?>
        <?php if (isset($_GET['success']) && $_GET['success'] == 2) echo "<p style='color:green;'>Đổi mật khẩu thành công!</p>"; ?>
        
        <div class="avatar-section">
            <?php if (!empty($avatar)): ?>
                <img src="assets/img/<?php echo htmlspecialchars($avatar); ?>" alt="Avatar" onerror="this.style.display='none';">
            <?php else: ?>
                <p>Chưa có avatar</p>
            <?php endif; ?>
        </div>

        <form method="POST" class="edit-form">
            <div>
                <label for="username">Tên người dùng:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>">
            </div>
            <button type="submit" class="save-btn">Lưu thay đổi thông tin</button>
        </form>

        <div class="password-section">
            <h3>Đổi mật khẩu</h3>
            <form method="POST" class="edit-form">
                <div>
                    <label for="current_password">Mật khẩu cũ:</label>
                    <input type="password" id="current_password" name="current_password" required>
                </div>
                <div>
                    <label for="new_password">Mật khẩu mới:</label>
                    <input type="password" id="new_password" name="new_password" required>
                </div>
                <div>
                    <label for="confirm_password">Xác nhận mật khẩu mới:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="save-btn">Lưu mật khẩu mới</button>
            </form>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>
</body>
</html>