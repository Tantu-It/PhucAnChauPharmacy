<?php
session_start();
include '../includes/db.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {

        $user = $res->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['username'] = $user['username'];

            $_SESSION['role'] = $user['role'];



            // Phân quyền

            if ($user['role'] === 'admin') {

                header("Location: ../admin.php");

            } else {

                header("Location: ../index.php");

            }

            exit;

        } else {

            $error_message = "Sai mật khẩu!";

        }

    } else {

        $error_message = "Tài khoản không tồn tại!";

    }

}

?>



<!DOCTYPE html>

<html lang="vi">



<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Phúc An Châu</title>
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
            <a href="../index.php">
                <h2>Phúc An Châu</h2>
            </a>
        </div>
    </nav>



    <div class="auth-container">

        <div class="auth-box">

            <h2>Đăng nhập</h2>

            <?php if ($error_message): ?>

                <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>

            <?php endif; ?>

            <form method="POST">

                <div class="input-group">

                    <label for="username">Tên đăng nhập</label>

                    <input type="text" id="username" name="username" placeholder="Nhập tên đăng nhập" required>

                </div>

                <div class="input-group">

                    <label for="password">Mật khẩu</label>

                    <div class="password-wrapper">

                        <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>

                        <span class="toggle-password" onclick="togglePassword()">👁️</span>

                    </div>

                </div>

                <button type="submit" class="auth-btn">Đăng nhập</button>

                <p class="register-link">Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>



            </form>

        </div>

    </div>


    <?php include '../includes/footer.php' ?>



    <script>

        function togglePassword() {

            const passwordInput = document.getElementById('password');

            const toggleIcon = document.querySelector('.toggle-password');

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