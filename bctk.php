<?php
session_start();
include 'includes/db.php';

// 1. Tổng số đơn hàng
$sql_total_orders = "SELECT COUNT(*) AS total_orders FROM orders";
$result_total_orders = $conn->query($sql_total_orders);
$total_orders = ($result_total_orders && $result_total_orders->num_rows > 0) ? $result_total_orders->fetch_assoc()['total_orders'] : 0;

// 2. Doanh thu từ các đơn có payment_status là 'completed'
$sql_total_revenue = "SELECT SUM(total_price) AS total_revenue FROM orders WHERE payment_status = 'completed'";
$result_total_revenue = $conn->query($sql_total_revenue);
$total_revenue = ($result_total_revenue && $result_total_revenue->num_rows > 0) ? $result_total_revenue->fetch_assoc()['total_revenue'] : 0;
if ($total_revenue === null) $total_revenue = 0;

// 3. Số đơn hàng theo trạng thái
$sql_payment_status_counts = "SELECT payment_status, COUNT(*) AS count_payment_status FROM orders GROUP BY payment_status";
$result_payment_status_counts = $conn->query($sql_payment_status_counts);
$payment_status_counts = [];
$payment_status_map = [
    'completed' => 'Hoàn thành',
    'processing' => 'Đang xử lí',
];
if ($result_payment_status_counts && $result_payment_status_counts->num_rows > 0) {
    while ($row = $result_payment_status_counts->fetch_assoc()) {
        $ten_trang_thai = $payment_status_map[$row['payment_status']] ?? ucfirst($row['payment_status']);
        $payment_status_counts[$ten_trang_thai] = [
            'count' => $row['count_payment_status'],
            'key' => $row['payment_status']
        ];
    }
}

// 4. Sản phẩm bán chạy nhất (dựa trên cột sold lớn nhất)
$sql_top_product = "SELECT name AS ten_san_pham, sold AS total_sold FROM products ORDER BY sold DESC LIMIT 1";
$result_top_product = $conn->query($sql_top_product);
$top_product = ($result_top_product && $result_top_product->num_rows > 0) ? $result_top_product->fetch_assoc() : null;
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Báo cáo & Thống kê</title>
    <style>
        body {
            margin: 0;
            font-family: 'Times new roman', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 16px;
            background-color: #eef4fb;
            color: #333;
            padding: 20px;
        }

        .dashboard-header {
            font-size: 40px;
            font-weight: 700;
            color: #007bff;
            text-align: center;
            margin-bottom: 30px;
        }

        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 25px;
            flex: 1 1 280px;
            max-width: 320px;
            display: flex;
            align-items: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }

        .stat-card .icon {
            font-size: 24px;
            color: #fff;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .icon-revenue {
            background-color: #0d6efd;
        }

        .icon-orders {
            background-color: #17a2b8;
        }

        .stat-card .info .value {
            font-size: 26px;
            font-weight: 700;
            color: #004085;
            margin-bottom: 6px;
        }

        .stat-card .info .label {
            font-size: 14px;
            color: #0d6efd;
        }

        .report-section {
            max-width: 1000px;
            margin: 0 auto 30px auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
            padding: 30px;
        }

        .report-section h3 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 600;
            color: #007bff;
            border-bottom: 2px solid #cfe2ff;
            padding-bottom: 10px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        th,
        td {
            padding: 14px 12px;
            text-align: center;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #0d6efd;
            color: white;
            font-weight: 600;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: none;
        }

        td {
            font-size: 16px;
            color: #333;
        }

        .view-btn {
            background-color: #0d6efd;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            margin-left: 10px;
            font-size: 13px;
            display: inline-block;
            transition: background-color 0.3s ease;
        }

        .view-btn:hover {
            background-color: #084298;
        }
    </style>
</head>

<body>

    <div class="dashboard-header"> Báo cáo & Thống kê</div>

    <div class="stats-container">
        <div class="stat-card">
            <div class="icon icon-revenue">₫</div>
            <div class="info">
                <div class="value"><?= number_format($total_revenue, 0, ',', '.') ?></div>
                <div class="label">Tổng doanh thu (đã thanh toán)</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="icon icon-orders">🧾</div>
            <div class="info">
                <div class="value"><?= number_format($total_orders) ?></div>
                <div class="label">Tổng số đơn hàng</div>
            </div>
        </div>
    </div>

    <div class="report-section">
        <h3> Đơn hàng theo trạng thái</h3>
        <table>
            <thead>
                <tr>
                    <th>Trạng thái</th>
                    <th>Số lượng</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($payment_status_counts)): ?>
                    <?php foreach ($payment_status_counts as $payment_status_vi => $info): ?>
                        <tr>
                            <td><?= $payment_status_vi ?></td>
                            <td>
                                <?= number_format($info['count']) ?>
                                <a href="qldh.php?payment_status=<?= urlencode($info['key']) ?>" class="view-btn">Xem</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Không có dữ liệu.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <h3 style="margin-top: 40px;"> Sản phẩm bán chạy nhất</h3>
        <table>
            <head>
                <tr>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng đã bán</th>
                </tr>
            </head>
            <body>
                <?php if ($top_product): ?>
                    <tr>
                        <td><?= htmlspecialchars($top_product['ten_san_pham']) ?></td>
                        <td><?= number_format($top_product['total_sold']) ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td colspan="2">Chưa có dữ liệu.</td>
                    </tr>
                <?php endif; ?>

                
            </body>
        </table>
    </div>

</body>

</html>