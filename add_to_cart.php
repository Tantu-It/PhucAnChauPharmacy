<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}


$user_id = $_SESSION['user_id'];
$product_id = intval($_POST['product_id']);
$quantity = intval($_POST['quantity']);

if ($quantity < 1) {
    $quantity = 1;
}

// Kiểm tra sản phẩm đã có trong giỏ chưa
$sql_check = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Nếu đã có thì cập nhật số lượng
    $row = $result->fetch_assoc();
    $new_quantity = $row['quantity'] + $quantity;

    $update = "UPDATE cart SET quantity = ? WHERE id = ?";
    $stmt = $conn->prepare($update);
    $stmt->bind_param("ii", $new_quantity, $row['id']);
    $stmt->execute();
} else {
    // Nếu chưa có thì thêm mới
    $insert = "INSERT INTO cart (user_id, product_id, quantity, added_at) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert);
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
}

header("Location: cart.php");
exit;
?>
