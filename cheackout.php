<?php

// Khởi tạo session

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}

include 'includes/db.php';



// Kiểm tra đăng nhập

if (!isset($_SESSION['user_id'])) {

    header("Location: auth/login.php");

    exit;

}



// Lấy thông tin người dùng

$user_id = $_SESSION['user_id'];
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';



// Truy vấn dữ liệu giỏ hàng của người dùng

$sql = "

    SELECT c.id as cart_id, p.id as product_id, p.name, p.price, p.discount, p.final_price, c.quantity

    FROM cart c

    JOIN products p ON c.product_id = p.id

    WHERE c.user_id = ?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();



$total = 0;

$items = [];

if ($result->num_rows > 0) {

    while ($item = $result->fetch_assoc()) {

        $subtotal = $item['final_price'] * $item['quantity']; // Sử dụng final_price để tính tổng

        $total += $subtotal;

        $items[] = $item;

    }

} else {

    header("Location: cart.php");

    exit;

}



// Lấy thông tin người dùng từ bảng users để điền sẵn (nếu có)

$sql_user = "SELECT email FROM users WHERE id = ?";

$stmt_user = $conn->prepare($sql_user);

$stmt_user->bind_param("i", $user_id);

$stmt_user->execute();

$user = $stmt_user->get_result()->fetch_assoc();

?>



<!DOCTYPE html>

<html lang="vi">



<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thanh toán - Phúc An Châu</title>

    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">

    <link rel="stylesheet" href="assets/css/checkout.css">

    <link rel="stylesheet" href="assets/css/Trang_chu.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>

        .form-group {

            margin-bottom: 15px;

        }

        .form-group label {

            display: block;

            margin-bottom: 5px;

            font-weight: bold;

        }

        .form-group input, .form-group textarea, .form-group select {

            width: 100%;

            padding: 8px;

            border: 1px solid #ddd;

            border-radius: 4px;

            box-sizing: border-box;

        }

        .error-message {

            color: red;

            font-size: 0.9em;

            display: none;

        }

    </style>

</head>



<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <div class="checkout-container">

        <h2><i class="fas fa-user"></i> Thông tin khách hàng</h2>

        <form method="POST" action="place_order.php" class="checkout-form" id="checkoutForm">

            <div class="form-group">

                <label for="full_name">Họ và tên:</label>

                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($username); ?>" required>

                <span class="error-message" id="full_name_error">Vui lòng nhập họ và tên.</span>

            </div>

            <div class="form-group">

                <label for="phone">Số điện thoại:</label>

                <input type="text" id="phone" name="phone" pattern="[0-9]{10}" required>

                <span class="error-message" id="phone_error">Vui lòng nhập số điện thoại hợp lệ (10 số).</span>

            </div>

            <div class="form-group">

                <label for="email">Email:</label>

                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>

                <span class="error-message" id="email_error">Vui lòng nhập email hợp lệ.</span>

            </div>

            <div class="form-group">

                <label for="address">Địa chỉ giao hàng:</label>

                <textarea id="address" name="address" required></textarea>

                <span class="error-message" id="address_error">Vui lòng nhập địa chỉ giao hàng.</span>

            </div>



            <h2><i class="fas fa-credit-card"></i> Chọn phương thức thanh toán</h2>

            <div class="payment-methods">

                <label>

                    <input type="radio" name="payment_method" value="qrcode" checked>

                    <i class="fas fa-qrcode"></i> Thanh toán bằng chuyển khoản (QR Code)

                </label>

                <label>

                    <input type="radio" name="payment_method" value="atm">

                    <i class="fas fa-university"></i> Thanh toán bằng thẻ ATM nội địa và tài khoản ngân hàng

                </label>

            </div>



            <h2><i class="fas fa-shopping-cart"></i> Chi tiết giỏ hàng</h2>

            <div class="cart-items">

                <?php foreach ($items as $item):

                    $subtotal = $item['final_price'] * $item['quantity'];

                ?>

                    <div class="cart-item">

                        <span class="item-name"><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>

                        <?php if ($item['discount'] > 0): ?>

                            <span class="item-price"><s><?php echo number_format($item['price']); ?> VNĐ</s> <span class="discounted-price"><?php echo number_format($item['final_price']); ?> VNĐ</span></span>

                        <?php else: ?>

                            <span class="item-price"><?php echo number_format($item['price']); ?> VNĐ</span>

                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </div>



            <div class="order-summary">

                <p>Tổng tiền: <span class="total-amount"><?php echo number_format($total); ?> VNĐ</span></p>

                <p>Thành tiền: <span class="final-amount"><?php echo number_format($total); ?> VNĐ</span></p>

            </div>



            <!-- Truyền dữ liệu giỏ hàng và tổng tiền qua hidden inputs -->

            <input type="hidden" name="total" value="<?php echo $total; ?>">

            <?php foreach ($items as $index => $item): ?>

                <input type="hidden" name="items[<?php echo $index; ?>][product_id]" value="<?php echo $item['product_id']; ?>">

                <input type="hidden" name="items[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>">

                <input type="hidden" name="items[<?php echo $index; ?>][quantity]" value="<?php echo $item['quantity']; ?>">

                <input type="hidden" name="items[<?php echo $index; ?>][price]" value="<?php echo $item['final_price']; ?>">

            <?php endforeach; ?>



            <button type="submit" name="place_order" class="place-order-btn"><i class="fas fa-check-circle"></i> Xác nhận mua hàng</button>

            <p>Bằng việc tiến hành đặt mua hàng, bạn đồng ý với <a href="#">Điều khoản dịch vụ</a> và <a href="#">Chính sách xử lý dữ liệu cá nhân</a> của Nhà thuốc Phúc An Châu.</p>

        </form>

    </div>
        <?php include 'includes/footer.php' ?>



    <script>

        document.querySelector('.menu-toggle').addEventListener('click', function() {

            document.querySelector('.nav-links').classList.toggle('active');

        });



        // Validation form

        const form = document.getElementById('checkoutForm');

        form.addEventListener('submit', function(e) {

            let isValid = true;



            // Validate Full Name

            const fullName = document.getElementById('full_name').value.trim();

            if (fullName === '') {

                document.getElementById('full_name_error').style.display = 'block';

                isValid = false;

            } else {

                document.getElementById('full_name_error').style.display = 'none';

            }



            // Validate Phone

            const phone = document.getElementById('phone').value.trim();

            const phonePattern = /^[0-9]{10}$/;

            if (!phonePattern.test(phone)) {

                document.getElementById('phone_error').style.display = 'block';

                isValid = false;

            } else {

                document.getElementById('phone_error').style.display = 'none';

            }



            // Validate Email

            const email = document.getElementById('email').value.trim();

            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {

                document.getElementById('email_error').style.display = 'block';

                isValid = false;

            } else {

                document.getElementById('email_error').style.display = 'none';

            }



            // Validate Address

            const address = document.getElementById('address').value.trim();

            if (address === '') {

                document.getElementById('address_error').style.display = 'block';

                isValid = false;

            } else {

                document.getElementById('address_error').style.display = 'none';

            }



            if (!isValid) {

                e.preventDefault();

            }

        });

    </script>

</body>



</html>