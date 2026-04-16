<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';




// Lấy thông tin đơn hàng mới nhất của người dùng
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if ($order) {
    // Lấy chi tiết các sản phẩm trong đơn hàng
    $order_id = $order['id'];
    $sql_items = "SELECT od.quantity, p.name, p.price, p.discount, p.final_price 
                  FROM order_details od
                  JOIN products p ON od.product_id = p.id
                  WHERE od.order_id = ?";
    $stmt_items = $conn->prepare($sql_items);
    $stmt_items->bind_param("i", $order_id);
    $stmt_items->execute();
    $items_result = $stmt_items->get_result();
} else {
    echo "<p>No order found for this user.</p>";
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Nhà thuốc Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="assets/css/thank_you.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>
    <main class="container">
        <div class="thank-you-container">
            <h2>Cảm ơn bạn đã đặt hàng!</h2>
            <p>Đơn hàng của bạn đã được tiếp nhận và đang trong trạng thái chờ xử lý. Dưới đây là chi tiết đơn hàng của bạn:</p>

            <h3>Thông tin đơn hàng #<?php echo $order['id']; ?></h3>
            <p><strong>Tổng giá trị đơn hàng:</strong> <?php echo number_format($order['total_price']); ?> VNĐ</p>
            <p><strong>Ngày đặt:</strong> <?php echo date("d/m/Y H:i:s", strtotime($order['order_date'])); ?></p>

            <h4>Chi tiết sản phẩm:</h4>
            <?php if ($items_result->num_rows > 0): ?>
                <ul>
                    <?php while ($item = $items_result->fetch_assoc()): ?>
                        <li>
                            <strong><?php echo htmlspecialchars($item['name']); ?></strong> - <?php echo $item['quantity']; ?> x
                            <?php if ($item['discount'] > 0): ?>
                                <span class="original-price"><s><?php echo number_format($item['price']); ?> VNĐ</s></span>
                                <span class="discounted-price"><?php echo number_format($item['final_price']); ?> VNĐ</span>
                            <?php else: ?>
                                <?php echo number_format($item['price']); ?> VNĐ
                            <?php endif; ?>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No items found for this order.</p>
            <?php endif; ?>
            <p>Chúng tôi sẽ liên hệ với bạn sớm để xác nhận đơn hàng và giao hàng.</p>
            <a href="index.php">Quay lại trang chủ</a>
        </div>
        <?php include'includes/recently.php'?>
        </main>
        <?php include 'includes/footer.php' ?>
</body>
</html>