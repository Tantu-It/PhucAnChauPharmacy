<?php
include 'includes/db.php';
session_start();
include 'send_order_email.php'; // Đảm bảo file này đã được include

// Kiểm tra quyền admin (nếu cần)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'error' => 'Không có quyền truy cập']);
    exit;
}

// Xử lý thay đổi trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = 'completed';
    $sql_update = "UPDATE orders SET payment_status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("si", $payment_status, $order_id);
    if ($stmt->execute()) {
        // Gửi email thông báo khi thanh toán hoàn tất
        $email_result = sendOrderEmail($order_id, $conn);
        if ($email_result['status'] === 'error') {
            error_log("Failed to send order email for order #$order_id: " . $email_result['message']);
            echo json_encode(['success' => true, 'order_id' => $order_id, 'email_error' => 'Gửi email thất bại']);
        } else {
            echo json_encode(['success' => true, 'order_id' => $order_id]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Cập nhật thất bại']);
    }
    exit;
}

$sql = "SELECT * FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý đơn hàng - Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <h2>Danh sách đơn hàng</h2>
    <table>
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Thời gian</th>
                <th>Tổng tiền</th>
                <th>Trạng thái thanh toán</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $result->fetch_assoc()): ?>
                <tr>
                    <td>#<?php echo $order['id']; ?></td>
                    <td><?php echo date("d/m/Y H:i:s", strtotime($order['order_date'])); ?></td>
                    <td><?php echo number_format($order['total_price']); ?> VNĐ</td>
                    <td><?php echo ucfirst($order['payment_status']); ?></td>
                    <td>
                        <?php if ($order['payment_status'] === 'processing'): ?>
                            <button class="update-status-btn" data-order-id="<?php echo $order['id']; ?>">Xác nhận đã thanh toán</button>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
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
                    data: { update_status: true, order_id: orderId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            alert('Cập nhật trạng thái thành công!');
                            if (response.email_error) {
                                alert('Lưu ý: Gửi email thông báo thất bại.');
                            }
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

<style>
table {
    width: 90%;
    margin: 20px auto;
    border-collapse: collapse;
    background-color: #fff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
}

th, td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

th {
    background-color: #007bff;
    color: #fff;
}

tr:nth-child(even) {
    background-color: #f9fafb;
}

tr:hover {
    background-color: #e5e7eb;
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
</style>