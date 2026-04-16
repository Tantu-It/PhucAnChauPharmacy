<?php
session_start();
include 'includes/db.php';

// Kiểm tra quyền quản trị viên
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth/login.php");
    exit;
}

// Xử lý thay đổi trạng thái thanh toán
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = 'completed';
    $sql_update = "UPDATE orders SET payment_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $payment_status, $order_id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'order_id' => $order_id]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Cập nhật thất bại']);
    }
    exit;
}

// Điều kiện lọc
// Điều kiện lọc
$where = "1=1";

if (isset($_GET['tu_khoa']) && $_GET['tu_khoa'] !== '') {
    $tu_khoa = $conn->real_escape_string($_GET['tu_khoa']);
    $where .= " AND o.id = '$tu_khoa'"; // Chỉ tìm kiếm theo ID đơn hàng
}

if (isset($_GET['payment_status']) && $_GET['payment_status'] !== '') {
    $payment_status = $conn->real_escape_string($_GET['payment_status']);
    $where .= " AND o.payment_status = '$payment_status'";
}

$sql = "SELECT 
            o.id, 
            u.username AS ten_khach_hang, 
            o.total_price AS tong_tien, 
            o.order_date AS ngay_tao,
            o.payment_status
        FROM orders o
        LEFT JOIN users u ON o.user_id = u.id
        WHERE $where
        ORDER BY o.order_date DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Phúc An Châu</title>
    <style>
        body {
            font-family: 'Times New Roman';
            background-color: #f9f9f9;
            padding: 20px;
            color: #333333;
        }

        h2 {
            text-align: center;
            background-image: linear-gradient(to right, #007bff, #00bfff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .filter {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 12px;
            margin-bottom: 30px;
            background: #e6f2ff;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 123, 255, 0.1);
        }

        .filter input[type=text],
        .filter select {
            padding: 10px;
            width: 280px;
            border-radius: 8px;
            border: 1px solid #007bff;
            outline: none;
            box-sizing: border-box;
            color: #333;
        }

        .filter input[type=submit] {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 600;
        }

        .filter input[type=submit]:hover {
            background-color: #0056b3;
        }

        table {
            width: 95%;
            margin: 0 auto 40px;
            border-collapse: collapse;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0, 123, 255, 0.12);
        }

        th,
        td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #d6eaff;
            vertical-align: middle;
            color: #333333;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        td a {
            color: #007bff;
            text-decoration: none;
            font-weight: 500;
        }

        td a:hover {
            text-decoration: underline;
        }

        .update-status-btn {
            padding: 5px 10px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .update-status-btn:hover {
            background-color: #218838;
        }

        @media screen and (max-width: 768px) {

            table,
            thead,
            tbody,
            th,
            td,
            tr {
                display: block;
            }

            tr {
                margin-bottom: 1rem;
            }

            td {
                text-align: right;
                position: relative;
                padding-left: 50%;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 12px;
                white-space: nowrap;
                font-weight: bold;
                color: #007bff;
            }

            th {
                display: none;
            }
        }
    </style>
</head>

<body>

    <h2>Danh sách đơn hàng<?= isset($payment_status) ? ' - ' . ucfirst(htmlspecialchars($payment_status)) : '' ?></h2>

    <!-- FORM TÌM KIẾM & LỌC -->
    <form class="filter" method="GET">
        <input type="text" name="tu_khoa" placeholder="🔍 Nhập ID đơn hàng..." value="<?= htmlspecialchars($_GET['tu_khoa'] ?? '') ?>">

 <select name="payment_status">
    <option value="">-- Tất cả trạng thái thanh toán --</option>
    <option value="processing" <?= (($_GET['payment_status'] ?? '') == 'processing') ? 'selected' : '' ?>>Đang xử lý</option>
    <option value="completed" <?= (($_GET['payment_status'] ?? '') == 'completed') ? 'selected' : '' ?>>Hoàn thành</option>
</select>

        <input type="submit" value="Lọc đơn hàng">
    </form>

    <!-- BẢNG KẾT QUẢ -->
    <table>
        <thead>
            <tr>
                <th>ID Đơn</th>
                <th>Tên khách hàng</th>
                <th>Tổng tiền (VNĐ)</th>
                <th>Trạng thái thanh toán</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td data-label="ID Đơn"><a href="ctdh.php?id=<?= $row['id'] ?>"><?= $row['id'] ?></a></td>
                        <td data-label="Tên khách hàng"><?= htmlspecialchars($row['ten_khach_hang']) ?></td>
                        <td data-label="Tổng tiền (VNĐ)"><?= number_format($row['tong_tien'], 0, ',', '.') ?></td>
                        <td data-label="Trạng thái thanh toán"><?= ucfirst($row['payment_status']) ?></td>
                        <td data-label="Ngày tạo"><?= date("d/m/Y H:i:s", strtotime($row['ngay_tao'])) ?></td>
                        <td data-label="Hành động">
                            <?php if ($row['payment_status'] === 'processing'): ?>
                                <button class="update-status-btn" data-order-id="<?= $row['id'] ?>">Xác nhận đã thanh toán</button>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Không tìm thấy đơn hàng phù hợp.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.update-status-btn').click(function() {
                var orderId = $(this).data('order-id');
                $.ajax({
                    url: 'admin_orders.php',
                    type: 'POST',
                    data: {
                        update_status: true,
                        order_id: orderId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Cập nhật trạng thái thanh toán thành công!');
                            location.reload(); // Tải lại trang để cập nhật
                        } else {
                            alert('Lỗi: ' + (response.error || 'Không thể cập nhật'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error: ' + status + ' - ' + error);
                        alert('Có lỗi xảy ra khi gửi yêu cầu!');
                    }
                });
            });
        });
    </script>

</body>

</html>