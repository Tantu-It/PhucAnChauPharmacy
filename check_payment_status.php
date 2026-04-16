<?php
header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

if (!isset($_GET['order_id'])) {
    echo json_encode(['error' => 'Thiếu ID đơn hàng']);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Không đăng nhập']);
    exit;
}

$order_id = intval($_GET['order_id']);
$user_id = $_SESSION['user_id'];
$sql = "SELECT payment_status FROM orders WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    error_log("Prepare failed: " . $conn->error);
    echo json_encode(['error' => 'Lỗi cơ sở dữ liệu']);
    exit;
}
$stmt->bind_param("ii", $order_id, $user_id);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['error' => 'Lỗi truy vấn']);
    exit;
}
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $order = $result->fetch_assoc();
    echo json_encode(['payment_status' => $order['payment_status']]);
} else {
    echo json_encode(['error' => 'Không tìm thấy đơn hàng hoặc không có quyền']);
}
$stmt->close();
?>