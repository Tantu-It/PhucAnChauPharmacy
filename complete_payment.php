<?php
include 'send_order_email.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_POST['order_id'])) {
    header("Location: cheackout.php");
    exit;
}

$order_id = $_POST['order_id'];

// Giả lập thanh toán thành công
header("Location: thank_you.php?order_id=" . $order_id);
exit;
?>