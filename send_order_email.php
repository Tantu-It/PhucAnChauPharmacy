<?php
require 'vendor/vendor/autoload.php'; // Nếu dùng Composer
// Nếu không dùng Composer, thay bằng:
// require 'vendor/PHPMailer/src/PHPMailer.php';
// require 'vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'includes/db.php';

function sendOrderEmail($order_id, $conn) {
    // Lấy thông tin đơn hàng
    $sql_order = "SELECT o.*, u.username 
                  FROM orders o 
                  LEFT JOIN users u ON o.user_id = u.id 
                  WHERE o.id = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();
    $order = $stmt_order->get_result()->fetch_assoc();

    if (!$order || $order['payment_status'] !== 'completed') {
        return ['status' => 'error', 'message' => 'Order not found or payment not completed'];
    }

    // Lấy chi tiết đơn hàng
    $sql_details = "SELECT od.quantity, od.price, p.name 
                    FROM order_details od 
                    JOIN products p ON od.product_id = p.id 
                    WHERE od.order_id = ?";
    $stmt_details = $conn->prepare($sql_details);
    $stmt_details->bind_param("i", $order_id);
    $stmt_details->execute();
    $details = $stmt_details->get_result()->fetch_all(MYSQLI_ASSOC);

    // Tạo nội dung email
    $subject = "Đơn hàng #{$order['id']} - Đặt hàng thành công tại Phúc An Châu";
    $body = "<h2>Đơn hàng đặt thành công</h2>";
    $body .= "<p>Xin chào <strong>{$order['full_name']}</strong>,</p>";
    $body .= "<p>Cảm ơn bạn đã đặt hàng tại Phúc An Châu! Dưới đây là thông tin đơn hàng của bạn:</p>";
    $body .= "<h3>Thông tin đơn hàng</h3>";
    $body .= "<p><strong>Mã đơn hàng:</strong> #{$order['id']}</p>";
    $body .= "<p><strong>Ngày đặt hàng:</strong> " . date('d/m/Y H:i', strtotime($order['order_date'])) . "</p>";
    $body .= "<p><strong>Tổng tiền:</strong> " . number_format($order['total_price'], 0, ',', '.') . " ₫</p>";
    $body .= "<p><strong>Trạng thái thanh toán:</strong> " . ($order['payment_status'] == 'completed' ? 'Đã thanh toán' : 'Đang xử lý') . "</p>";
    $body .= "<h3>Chi tiết sản phẩm</h3>";
    $body .= "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    $body .= "<tr><th>Sản phẩm</th><th>Số lượng</th><th>Giá</th><th>Thành tiền</th></tr>";
    foreach ($details as $item) {
        $subtotal = $item['quantity'] * $item['price'];
        $body .= "<tr>";
        $body .= "<td>{$item['name']}</td>";
        $body .= "<td style='text-align: center;'>{$item['quantity']}</td>";
        $body .= "<td style='text-align: right;'>" . number_format($item['price'], 0, ',', '.') . " ₫</td>";
        $body .= "<td style='text-align: right;'>" . number_format($subtotal, 0, ',', '.') . " ₫</td>";
        $body .= "</tr>";
    }
    $body .= "</table>";
    $body .= "<h3>Thông tin khách hàng</h3>";
    $body .= "<p><strong>Tên khách hàng:</strong> {$order['full_name']}</p>";
    $body .= "<p><strong>Email:</strong> {$order['email']}</p>";
    $body .= "<p><strong>Số điện thoại:</strong> {$order['phone']}</p>";
    $body .= "<p><strong>Địa chỉ giao hàng:</strong> {$order['address']}</p>";
    $body .= "<p>Chúng tôi sẽ sớm liên hệ để xác nhận và giao hàng. Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ qua email: <a href='mailto:support@phucanchau.com'>support@phucanchau.com</a> hoặc số hotline: 0123 456 789.</p>";
    $body .= "<p>Trân trọng,</p>";
    $body .= "<p><strong>Nhà thuốc Phúc An Châu</strong></p>";

    // Cấu hình PHPMailer
    $mail = new PHPMailer(true);
    try {
        // Cài đặt SMTP
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nguyentantu2005bt@gmail.com'; // Gmail của bạn
        $mail->Password   = 'vluocnielhkpphas'; // Mật khẩu ứng dụng
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Thiết lập email
        $mail->setFrom('nguyentantu2005bt@gmail.com', 'Phuc An Chau');
        $mail->addAddress($order['email'], $order['full_name']);
        $mail->addReplyTo('support@phucanchau.com', 'Phuc An Chau Support');

        // Nội dung email
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = strip_tags($body); // Nội dung dạng text thuần cho client không hỗ trợ HTML

        // Gửi email
        $mail->send();
        return ['status' => 'success', 'message' => 'Email sent successfully'];
    } catch (Exception $e) {
        return ['status' => 'error', 'message' => "Failed to send email: {$mail->ErrorInfo}"];
    }
}
?>