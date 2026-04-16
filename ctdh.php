<?php
session_start();
include 'includes/db.php'; // Kết nối database

// Lấy ID đơn hàng
$don_hang_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($don_hang_id <= 0) {
    echo "ID đơn hàng không hợp lệ.";
    exit;
}

// Lấy từ khóa tìm kiếm nếu có
$tu_khoa = isset($_GET['tu_khoa']) ? trim($_GET['tu_khoa']) : '';

// Truy vấn thông tin đơn hàng và khách hàng
$sql_don = "SELECT 
                o.id, 
                o.total_price AS tong_tien, 
                o.status AS trang_thai, 
                o.order_date AS ngay_tao, 
                u.username AS ten_khach_hang
            FROM orders o
            LEFT JOIN users u ON o.user_id = u.id
            WHERE o.id = $don_hang_id";
$result_don = $conn->query($sql_don);
$don_hang = $result_don->fetch_assoc();

// Truy vấn chi tiết đơn hàng kèm tên sản phẩm, có lọc theo từ khóa nếu có
$sql_chi_tiet = "SELECT 
                    oi.quantity AS so_luong, 
                    p.name AS ten_san_pham
                FROM order_items oi
                LEFT JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = $don_hang_id";

if ($tu_khoa !== '') {
    $tu_khoa_safe = $conn->real_escape_string($tu_khoa);
    $sql_chi_tiet .= " AND p.name LIKE '%$tu_khoa_safe%'";
}

$result_chi_tiet = $conn->query($sql_chi_tiet);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng #<?= $don_hang_id ?></title>
    <style>
/* Toàn bộ phần tử tính box-sizing dễ kiểm soát padding, border */
*,
*::before,
*::after {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    color: #333;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #0d6efd; /* xanh dương */
    margin-bottom: 30px;
    font-weight: 700;
    font-size: 28px;
}

.info {
    max-width: 600px;
    margin: 0 auto 30px auto;
    background-color: #fff;
    border-radius: 12px;
    box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    padding: 25px;
    color: #004085;
    font-weight: 600;
    line-height: 1.6;
    text-align: center;
}

form.filter {
    max-width: 600px;
    margin: 0 auto 40px auto;
    text-align: center;
}

form.filter input[type="text"] {
    padding: 10px 14px;
    width: 320px;
    border-radius: 12px;
    border: 1.5px solid #0d6efd;
    font-size: 16px;
    outline: none;
    transition: border-color 0.3s ease;
}

form.filter input[type="text"]:focus {
    border-color: #0056b3;
}

form.filter input[type="submit"] {
    background-color: #0d6efd;
    color: white;
    border: none;
    padding: 10px 22px;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 700;
    font-size: 16px;
    margin-left: 12px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

form.filter input[type="submit"]:hover {
    background-color: #004085;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

table {
    width: 100%;
    max-width: 600px;
    margin: 0 auto 40px auto;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    background-color: #fff;
}

th, td {
    padding: 14px 16px;
    text-align: center;
    border-bottom: 1px solid #cfe2ff;
    border-right: 1px solid #cfe2ff;
    font-size: 16px;
}

th {
    background-color: #0d6efd;
    color: #fff;
    font-weight: 700;
    user-select: none;
}

td:last-child, th:last-child {
    border-right: none;
}

tr:last-child td {
    border-bottom: none;
}

tr:hover td {
    background-color: #e9f2ff;
    transition: background-color 0.3s ease;
}

.btn {
    display: inline-block;
    background-color: #0d6efd;
    color: white;
    padding: 12px 28px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    margin-top: 10px;
    user-select: none;
}

.btn:hover {
    background-color: #004085;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

/* Responsive cho bảng khi trên mobile */
@media (max-width: 640px) {
    table, thead, tbody, th, td, tr {
        display: block;
    }
    thead tr {
        display: none;
    }
    tr {
        margin-bottom: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        background: #fff;
        padding: 15px;
    }
    td {
        text-align: right;
        padding-left: 50%;
        position: relative;
        border: none;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }
    td::before {
        content: attr(data-label);
        position: absolute;
        left: 15px;
        top: 14px;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 12px;
        color: #0d6efd;
    }
    td:last-child {
        border-bottom: none;
    }
}
    </style>
</head>
<body>
    

    <div class="auth-container">
        <div class="auth-box" style="max-width:800px;width:100%;">
            <h2>📦 Chi tiết đơn hàng #<?= $don_hang_id ?></h2>
            <?php if ($don_hang): ?>
                <div class="info">
                    <p><strong>Khách hàng:</strong> <?= htmlspecialchars($don_hang['ten_khach_hang']) ?></p>
                    <p><strong>Ngày tạo:</strong> <?= $don_hang['ngay_tao'] ?></p>
                    <p><strong>Trạng thái:</strong> <?= htmlspecialchars($don_hang['trang_thai']) ?></p>
                    <p><strong>Tổng tiền:</strong> <?= number_format($don_hang['tong_tien'], 0, ',', '.') ?> VNĐ</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result_chi_tiet->num_rows > 0): ?>
                            <?php $stt = 1; while ($row = $result_chi_tiet->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $stt++ ?></td>
                                    <td><?= htmlspecialchars($row['ten_san_pham']) ?></td>
                                    <td><?= $row['so_luong'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3">Không có sản phẩm phù hợp với từ khóa tìm kiếm.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div style="text-align: center;">
                    <a href="qldh.php" class="btn">← Quay lại danh sách đơn hàng</a>
                </div>
            <?php else: ?>
                <p style="text-align: center;">Không tìm thấy thông tin đơn hàng.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
