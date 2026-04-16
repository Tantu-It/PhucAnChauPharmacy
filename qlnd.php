<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'includes/db.php';

// ===== XỬ LÝ CẬP NHẬT MẬT KHẨU =====
if (isset($_POST['cap_nhat_mat_khau'])) {
    $user_id = intval($_POST['user_id']);
    $new_password = $_POST['new_password'];

    if (strlen($new_password) < 6) {
        echo "<script>alert('Mật khẩu phải có ít nhất 6 ký tự.');</script>";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật mật khẩu thành công.'); window.location = '" . $_SERVER['PHP_SELF'] . "';</script>";
            exit;
        } else {
            echo "<script>alert('Lỗi khi cập nhật mật khẩu: " . $conn->error . "');</script>";
        }
    }
}

// ===== XỬ LÝ TÌM KIẾM =====
$where = "1=1";
if (isset($_GET['tu_khoa']) && $_GET['tu_khoa'] !== '') {
    $tu_khoa = $conn->real_escape_string($_GET['tu_khoa']);
    $where .= " AND (username LIKE '%$tu_khoa%' OR email LIKE '%$tu_khoa%')";
}

$sql = "SELECT id, username, email, role, created_at 
        FROM users 
        WHERE $where 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Lỗi truy vấn cơ sở dữ liệu: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý người dùng - Nhà thuốc</title>
    <style>
     
* {
    box-sizing: border-box;
    font-size: 16px; /* cỡ chữ chuẩn */
    font-family: Times new roman;
}
body {
    background-color: #f4f7fc;
    padding: 30px;
    color: #333;
}

h2 {
    text-align: center;
    color: #007bff;
    margin-bottom: 30px;
    font-size: 36px;
}

/* Thanh tìm kiếm */
.filter {
    max-width: 600px;
    margin: 0 auto 30px auto;
    display: flex;
    gap: 10px;
    justify-content: center;
}

.filter input[type=text] {
    flex: 1;
    padding: 10px;
    border-radius: 8px;
    border: 1.5px solid #b6d4fe;
    background-color: #fff;
    transition: border-color 0.3s ease;
}

.filter input[type=text]:focus {
    border-color: #0b5ed7;
    outline: none;
}

.filter input[type=submit] {
    background-color: #0b5ed7;
    color: white;
    border: none;
    padding: 10px 16px;
    border-radius: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.filter input[type=submit]:hover {
    background-color: #084298;
}

/* Bảng người dùng */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(13, 110, 253, 0.15);
    border: 2px solid #0b5ed7;
    overflow: hidden;
}

th, td {
    padding: 14px 12px;
    text-align: center;
    border-bottom: 1px solid #d0e3ff;
}

th {
    background-color: #0b5ed7;
    color: white;
    font-weight: bold;
    font-size: 16px;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover {
    background-color: #eef5ff;
    transition: background-color 0.2s ease;
}

/* Form cập nhật mật khẩu inline */
.inline-password-form {
    margin-top: 10px;
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.inline-password-form input[type=password] {
    padding: 6px 10px;
    border: 1.5px solid #b6d4fe;
    border-radius: 6px;
    width: 150px;
    transition: border-color 0.3s ease;
}

.inline-password-form input[type=password]:focus {
    border-color: #0b5ed7;
    outline: none;
}

.inline-password-form button {
    background-color: #198754; /* xanh lá */
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 20px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.inline-password-form button:hover {
    background-color: #146c43;
}
.container{
    margin: auto ;
}
    </style>
</head>
<body>
    <div class="container">
            <h2>Quản lý người dùng</h2>
            <form class="filter" method="GET" action="">
                <input type="text" name="tu_khoa" placeholder="🔍 Tìm tên người dùng hoặc email..." value="<?= htmlspecialchars($_GET['tu_khoa'] ?? '') ?>" />
                <input type="submit" value="Tìm" />
            </form>
            <table>
                <head>
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                    </tr>
                </head>
                <body>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td class="role"><?= $row['role'] === 'admin' ? 'Quản trị viên' : 'Người dùng' ?></td>
                                <td>
                                    <?= $row['created_at'] ?>
                                    <form method="POST" class="inline-password-form" onsubmit="return confirm('Bạn có chắc muốn cập nhật mật khẩu cho <?= htmlspecialchars($row['username']) ?>?');">
                                        <input type="hidden" name="user_id" value="<?= $row['id'] ?>" />
                                        <input type="password" name="new_password" required placeholder="Mật khẩu mới" minlength="6" />
                                        <button type="submit" name="cap_nhat_mat_khau">Cập nhật</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="padding:20px; font-style: italic;">Không tìm thấy người dùng phù hợp.</td>
                        </tr>
                    <?php endif; ?>
                </body>
            </table>
    </div>
</body>
</html>
