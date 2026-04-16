<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

$sql = "
    SELECT c.id as cart_id, p.id as product_id, p.name, p.price, p.image, c.quantity, p.discount, p.final_price
    FROM cart c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Lỗi chuẩn bị truy vấn: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="assets/css/cart.css">
</head>

<body>

    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <main class="container">
    <div class="cart-container">
        <?php
        $originalTotal = 0;
        $totalDiscount = 0;
        $total = 0;
        $selectedItems = [];

        if ($result->num_rows > 0):
            while ($item = $result->fetch_assoc()):
                $originalPrice = $item['price'];
                $discount = $item['discount'] ?? 0;
                $finalPrice = $item['final_price'] ?? $originalPrice;
                $quantity = $item['quantity'];

                $originalSubtotal = $originalPrice * $quantity;
                $finalSubtotal = $finalPrice * $quantity;

                $originalTotal += $originalSubtotal;
                $total += $finalSubtotal;
                $totalDiscount += $originalSubtotal - $finalSubtotal;
                $selectedItems[$item['cart_id']] = [
                    'product_id' => $item['product_id'],
                    'name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['final_price']
                ];
        ?>
                <div class="cart-item">
                    <div class="cart-item-image">
                        <img src="assets/img/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" onerror="this.src='assets/img/placeholder.jpg'">
                        <?php if ($discount > 0): ?>
                            <span class="discount-tag">-<?php echo $discount; ?>%</span>
                        <?php endif; ?>
                    </div>
                    <div class="cart-item-details">
                        <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                        <p>
                            <?php if ($discount > 0): ?>
                                <span class="original-price"><s><?php echo number_format($originalPrice); ?> VNĐ</s></span>
                                <span class="discounted-price"><?php echo number_format($finalPrice); ?> VNĐ</span>
                            <?php else: ?>
                                Giá: <?php echo number_format($originalPrice); ?> VNĐ
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="cart-item-quantity">
                        <form method="POST" action="update_quantity.php">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, -1)">-</button>
                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" max="99" class="quantity-input">
                                <button type="button" class="quantity-btn" onclick="updateQuantity(this, 1)">+</button>
                            </div>
                            <button type="submit" class="update-btn">Cập nhật</button>
                        </form>
                    </div>
                    <div class="cart-item-subtotal">
                        <p>Thành tiền: <?php echo number_format($finalSubtotal); ?> VNĐ</p>
                    </div>
                    <div class="cart-item-actions">
                        <form method="POST" action="remove_from_cart.php">
                            <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                            <button type="submit" class="remove-btn">Xóa</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
            <div class="cart-summary">
                <div class="cart-summary-details">
                    <p>Tổng tiền: <span class="total-amount"><?php echo number_format($originalTotal); ?> VNĐ</span></p>
                    <p>Giảm giá: <span class="discount-amount"><?php echo number_format($totalDiscount); ?> VNĐ</span></p>
                    <p>Tiền phải trả: <span class="final-amount"><?php echo number_format($total); ?> VNĐ</span></p>
                </div>
                <form method="POST" action="cheackout.php">
                    <button type="submit" class="place-order-btn">Mua hàng</button>
                </form>
            </div>
        <?php else: ?>
            <div class="empty-cart">
                <p>Giỏ hàng của bạn đang trống.</p>
                <a href="index.php" class="continue-shopping-btn">Tiếp tục mua sắm</a>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/recently.php' ?>
    </main>
    <?php include 'includes/footer.php' ?>
    <script>
        function updateQuantity(button, change) {
            const input = button.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) + change;
            value = Math.max(1, Math.min(99, value));
            input.value = value;
            const form = input.closest('form');
            form.submit();
        }

        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.display = 'block';
            setTimeout(() => toast.style.display = 'none', 3000);
        }

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', (e) => {
                const action = form.action;
                if (action.includes('update_quantity')) {
                    showToast('Cập nhật số lượng thành công!');
                } else if (action.includes('remove_from_cart')) {
                    showToast('Đã xóa sản phẩm khỏi giỏ hàng!');
                }
            });
        });

        function updateTotal() {
            let newTotal = 0;
            document.querySelectorAll('.cart-item-select input[type="checkbox"]:checked').forEach(checkbox => {
                const cartId = checkbox.name.match(/selected_items\[(\d+)\]/)[1];
                const subtotalElement = checkbox.closest('.cart-item').querySelector('.cart-item-subtotal p');
                const subtotal = parseInt(subtotalElement.textContent.replace(/[^\d]/g, ''));
                newTotal += subtotal;
            });
            document.querySelector('.final-amount').textContent = newTotal.toLocaleString() + ' VNĐ';
            document.querySelector('input[name="total"]').value = newTotal / 100; // Chia 100 để khớp với đơn vị VND
        }
    </script>
</body>

</html>