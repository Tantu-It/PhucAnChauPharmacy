<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$role = $_SESSION['role'];

include 'includes/db.php';

// Truy vấn đơn hàng của người dùng hiện tại
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$orders = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <title>Đơn hàng của bạn</title>
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <style>
               *{
        padding:0;
        magin:0;
        box-sizing:border-box;
         font-family: 'times new roman', sans-serif;
    }
        .orders-container {
            margin: 20px 100px;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
            font-size: 24px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
            letter-spacing: 0.5px;
        }
        tr {
            transition: background-color 0.3s ease;
        }
        tr:hover {
            background-color: #f1f8ff;
        }
        .detail-row {
            display: none;
            background-color: #f9f9f9;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .detail-table th {
            background-color: blue;
            color: white;
            font-size: 13px;
        }
        .detail-table td {
            border: 1px solid #ddd;
        }
        .layout {
    display: flex;
    gap: 20px;
}

       .sidebar {
            width: 200px;
            background: #e9ecef;
            padding: 20px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background: #007bff;
            color: white;
        }

.content {
    flex: 1;
    text-align:center;
}

        button {
            padding: 8px 15px;
            background-color: #17a2b8;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #138496;
        }
        .no-orders {
            text-align: center;
            color: #666;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        @media (max-width: 768px) {
            table {
                display: block;
                overflow-x: auto;
            }
            th, td {
                min-width: 120px;
            }
            h2 {
                font-size: 20px;
            }
        }
    </style>

    <script>
        function toggleDetail(orderId) {
            var row = document.getElementById("details-" + orderId);
            row.style.display = (row.style.display === "table-row") ? "none" : "table-row";
        }
    </script>
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>
    <section class="orders-container">
    <div class="layout">
        <div class="sidebar">
            <a href="profile.php">Thông tin cá nhân</a>
            <a href="orders.php">Đơn hàng đã đặt</a>
            <a href="javascript:void(0)" onclick="showDevelopmentMessage()">Theo dõi đơn hàng</a>
        </div>

        <div class="content">
            <h2>Đơn hàng của bạn - <?= htmlspecialchars($username) ?></h2>

        <table>
            <thead>
                <tr>
                    <th>Họ tên</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Địa chỉ</th>
                    <th>Tổng tiền</th>
                    <th>Phương thức</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['full_name']) ?></td>
                        <td><?= htmlspecialchars($order['phone']) ?></td>
                        <td><?= htmlspecialchars($order['email']) ?></td>
                        <td><?= htmlspecialchars($order['address']) ?></td>
                        <td><?= number_format($order['total_price'], 0, ',', '.') ?>₫</td>
                        <td><?= $order['payment_method'] ?></td>
                        <td><?= $order['payment_status'] == 'completed' ? 'Đã thanh toán' : 'Đang xử lý' ?></td>
                        <td><?= $order['order_date'] ?></td>
                        <td><button onclick="toggleDetail(<?= $order['id'] ?>)">Xem</button></td>
                    </tr>
                    <tr id="details-<?= $order['id'] ?>" class="detail-row">
                        <td colspan="9">
                            <table class="detail-table">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $order_id = $order['id'];
                                    $detail_sql = "
                                        SELECT od.*, p.name AS product_name
                                        FROM order_details od
                                        JOIN products p ON od.product_id = p.id
                                        WHERE od.order_id = $order_id
                                    ";
                                    $detail_result = $conn->query($detail_sql);
                                    while ($detail = $detail_result->fetch_assoc()):
                                        $thanhtien = $detail['quantity'] * $detail['price'];
                                ?>
                                    <tr>
                                        <td><?= htmlspecialchars($detail['product_name']) ?></td>
                                        <td><?= $detail['quantity'] ?></td>
                                        <td><?= number_format($detail['price'], 0, ',', '.') ?>₫</td>
                                        <td><?= number_format($thanhtien, 0, ',', '.') ?>₫</td>
                                    </tr>
                                <?php endwhile; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        </div>
        <?php
        $stmt->close();
        $conn->close();
        ?>
        </div>
    </section>
     <?php include 'includes/footer.php' ?>

</body>
</html>