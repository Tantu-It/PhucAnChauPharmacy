<?php
// Khởi tạo session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra file cần thiết
if (!file_exists('includes/db.php') || !file_exists('send_order_email.php')) {
    die("Lỗi: Không tìm thấy file db.php hoặc send_order_email.php");
}

include 'includes/db.php';
include 'send_order_email.php';



$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];


// Xử lý khi nhấn "Xác nhận đã thanh toán"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_payment'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = 'completed';
    $sql_update = "UPDATE orders SET payment_status = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql_update);
    $stmt->bind_param("sii", $payment_status, $order_id, $user_id);
    if ($stmt->execute()) {
        // Gửi email thông báo
        $email_result = sendOrderEmail($order_id, $conn);
        if ($email_result['payment_status'] === 'error') {
            error_log("Failed to send order email for order #$order_id: " . $email_result['message']);
        }
        header("Location: thank_you.php?order_id=" . $order_id);
        exit;
    } else {
        die("Lỗi: Cập nhật trạng thái thanh toán thất bại.");
    }
}

// Xử lý khi nhấn "Xác nhận mua hàng"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $payment_method = $_POST['payment_method'] ?? 'qrcode';
    $total = floatval($_POST['total'] ?? 0);
    $items = $_POST['items'] ?? [];
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Kiểm tra thông tin đầu vào
    if (empty($full_name) || empty($phone) || empty($email) || empty($address) || empty($items) || $total <= 0) {
        die("Lỗi: Thông tin không hợp lệ. Vui lòng kiểm tra lại.");
    }

    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Lỗi: Email không hợp lệ.");
    }

    // Cập nhật số lượng đã bán
    foreach ($items as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);

        if ($product_id <= 0 || $quantity <= 0) {
            continue;
        }

        $update = $conn->prepare("UPDATE products SET sold = sold + ? WHERE id = ?");
        $update->bind_param("ii", $quantity, $product_id);
        $update->execute();
    }

    // Lưu đơn hàng vào database
    $order_date = date('Y-m-d H:i:s');
    $payment_status = 'processing';
    $payment_check_time = date('Y-m-d H:i:s', strtotime('+10 minutes'));
    $sql_order = "INSERT INTO orders (user_id, total_price, order_date, full_name, phone, email, address, payment_method, payment_status, payment_check_time) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("idssssssss", $user_id, $total, $order_date, $full_name, $phone, $email, $address, $payment_method, $payment_status, $payment_check_time);
    if (!$stmt_order->execute()) {
        die("Lỗi: Không thể lưu đơn hàng.");
    }
    $order_id = $conn->insert_id;

    // Lưu chi tiết đơn hàng
    $sql_detail = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt_detail = $conn->prepare($sql_detail);
    foreach ($items as $item) {
        $product_id = (int)($item['product_id'] ?? 0);
        $quantity = (int)($item['quantity'] ?? 0);
        $price = floatval($item['price'] ?? 0);
        if ($product_id > 0 && $quantity > 0 && $price > 0) {
            $stmt_detail->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt_detail->execute();
        }
    }

    // Xóa giỏ hàng
    $sql_clear = "DELETE FROM cart WHERE user_id = ?";
    $stmt_clear = $conn->prepare($sql_clear);
    $stmt_clear->bind_param("i", $user_id);
    $stmt_clear->execute();

    // Lưu order_id và payment_method vào session
    $_SESSION['order_id'] = $order_id;
    $_SESSION['payment_method'] = $payment_method;
} else {
    header("Location: checkout.php");
    exit;
}

// Lấy trạng thái hiện tại từ database
$sql = "SELECT payment_status, payment_check_time, payment_method FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if (!$order) {
    echo "<p>Không tìm thấy đơn hàng.</p>";
    exit;
}

$payment_status = $order['payment_status'];
$payment_check_time = $order['payment_check_time'];
$payment_method = $order['payment_method'];

// Lấy chi tiết sản phẩm trong đơn hàng
$sql_items = "SELECT od.quantity, p.name, p.price, p.discount, p.final_price 
              FROM order_details od
              JOIN products p ON od.product_id = p.id
              WHERE od.order_id = ?";
$stmt_items = $conn->prepare($sql_items);
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$items_result = $stmt_items->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/process_payment.css">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <!-- Payment Container -->
    <div class="payment-container">
        <h2>Thanh toán đơn hàng #<?php echo $order_id; ?></h2>
        <div class="payment-details">
            <?php if ($payment_method === 'qrcode'): ?>
                <h3><i class="fas fa-qrcode"></i> Thanh toán bằng QR Code</h3>
                <p>Quét mã QR dưới đây để thanh toán:</p>
                <img src="assets/img/qr.jpg" alt="QR Code" class="qr-code">
                <p><strong>Số tiền:</strong> <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
                <p><strong>Nội dung:</strong> Thanh toán đơn hàng #<?php echo $order_id; ?></p>
                <p><strong>Thời gian kiểm tra:</strong> <?php echo date('H:i d/m/Y', strtotime($payment_check_time)); ?></p>

            <?php elseif ($payment_method === 'atm' || $payment_method === 'international'): ?>
                <h3><i class="fas fa-credit-card"></i> Thanh toán bằng thẻ</h3>
                <p>Vui lòng nhập thông tin thẻ để thanh toán:</p>
                <form method="POST" action="complete_payment.php" class="payment-form">
                    <input type="text" name="card_number" placeholder="Số thẻ" required>
                    <input type="text" name="card_holder" placeholder="Tên chủ thẻ" required>
                    <input type="text" name="expiry_date" placeholder="Ngày hết hạn (MM/YY)" required>
                    <input type="text" name="cvv" placeholder="CVV" required>
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="confirm_payment" value="1">
                    <button type="submit" class="complete-btn">Thanh toán ngay</button>
                </form>

            <?php elseif ($payment_method === 'zalopay' || $payment_method === 'momo' || $payment_method === 'vnpay'): ?>
                <h3><i class="fas fa-mobile-alt"></i> Thanh toán bằng ví điện tử</h3>
                <p>Mở ứng dụng và quét mã QR để thanh toán:</p>
                <img src="assets/img/qr-placeholder.png" alt="QR Code" class="qr-code">
                <p><strong>Số tiền:</strong> <?php echo number_format($total, 0, ',', '.'); ?> VNĐ</p>
                <p><strong>Nội dung:</strong> Thanh toán đơn hàng #<?php echo $order_id; ?></p>
                <p><strong>Thời gian kiểm tra:</strong> <?php echo date('H:i d/m/Y', strtotime($payment_check_time)); ?></p>

            <?php else: ?>
                <p>Lỗi: Phương thức thanh toán không hợp lệ.</p>
            <?php endif; ?>

            <h3>Chi tiết sản phẩm:</h3>
            <?php if ($items_result->num_rows > 0): ?>
                <ul>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong> - <?php echo $item['quantity']; ?> x
                            <?php if ($item['discount'] > 0): ?>
                                <span class="original-price"><s><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</s></span>
                                <span class="discounted-price"><?php echo number_format($item['final_price'], 0, ',', '.'); ?> VNĐ</span>
                            <?php else: ?>
                                <?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>Không tìm thấy sản phẩm trong đơn hàng.</p>
            <?php endif; ?>
        </div>

        <div class="payment-status">
            <p>Trạng thái thanh toán: <strong><?php echo ucfirst($payment_status); ?></strong></p>
            <?php if ($payment_status === 'processing'): ?>
                <p>Hệ thống sẽ tự động kiểm tra sau khi bạn đã thanh toán.</p>
                <form method="POST" class="confirm-form">
                    <input type="hidden" name="order_id" value="<?php echo $order_id; ?>">
                    <input type="hidden" name="confirm_payment" value="1">
                </form>
            <?php elseif ($payment_status === 'completed'): ?>
                <p>Thanh toán đã hoàn tất! Bạn sẽ được chuyển hướng sau 5 giây.</p>
                <script>
                    setTimeout(() => {
                        window.location.href = 'thank_you.php?order_id=<?php echo $order_id; ?>';
                    }, 5000);
                </script>
            <?php endif; ?>
        </div>
    </div>


    <?php include 'includes/footer.php' ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Polling để kiểm tra trạng thái
        function checkPaymentStatus() {
            $.ajax({
                url: 'check_payment_status.php?order_id=<?php echo $order_id; ?>',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.payment_status === 'completed') {
                        $('.payment-status strong').text('Completed');
                        $('.payment-status .confirm-form').remove();
                        $('.payment-status p:nth-child(2)').html('Thanh toán đã hoàn tất! Bạn sẽ được chuyển hướng sau 5 giây.');
                        setTimeout(() => {
                            window.location.href = 'thank_you.php?order_id=<?php echo $order_id; ?>';
                        }, 5000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error checking payment status: ' + error);
                }
            });
        }

        <?php if ($payment_status === 'processing'): ?>
            setInterval(checkPaymentStatus, 10000);
        <?php endif; ?>
    </script>
</body>
</html>