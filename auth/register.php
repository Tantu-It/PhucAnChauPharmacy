<?php
include '../includes/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = trim($_POST['email']);

    if (empty($username) || empty($password) || empty($confirm_password) || empty($email)) {
        $error = "Vui lòng điền đầy đủ thông tin.";
    } elseif ($password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        // Kiểm tra username đã tồn tại chưa
        $check = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $check->bind_param("s", $username);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            $error = "Tên đăng nhập đã tồn tại.";
        } else {
            // Đăng ký người dùng
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $hashedPassword, $email);

            if ($stmt->execute()) {
                $success = "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
            } else {
                $error = "Lỗi đăng ký: " . $stmt->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Phúc An Châu</title>
    <link rel="stylesheet" href="../assets/css/Trang_chu.css">
    <link rel="stylesheet" href="../assets/css/auth.css">
</head>
<body>

    <header class="hero-header">
        <div class="header-content">
            <h1>Nhà thuốc Phúc An Châu</h1>
            <p>Chăm sóc sức khỏe của bạn, mọi lúc, mọi nơi</p>
            <a href="https://dichvucong.dav.gov.vn/congbothuoc/index" class="btn tra-cuu">Tra thuốc chính hãng</a>
        </div>
    </header>

    <nav class="navbar">
        <div class="logo">
            <a href="../Trang_chu.php">
                <h2>Phúc An Châu</h2>
            </a>
        </div>
    </nav>

    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng ký tài khoản</h2>
            <?php if ($error): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="success-message"><?php echo $success; ?></div>
            <?php else: ?>
                <form method="POST">
                    <div class="input-group">
                        <label for="username">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>
                    </div>
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Nhập email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">Mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
                            <span class="toggle-password" onclick="togglePassword('password')">👁️</span>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="confirm_password">Xác nhận mật khẩu</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Xác nhận mật khẩu" required>
                            <span class="toggle-password" onclick="togglePassword('confirm_password')">👁️</span>
                        </div>
                    </div>
                    <button type="submit" class="auth-btn">Đăng ký</button>
                    <p class="login-link">Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a></p>

                </form>
            <?php endif; ?>
        </div>
    </div>

  
     <?php include '../includes/footer.php' ?>
    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleIcon = passwordInput.nextElementSibling;
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '👁️‍🗨️';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
            }
        }
    </script>

</body>

</html>