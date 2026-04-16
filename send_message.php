<?php
require 'vendor/vendor/autoload.php';
include 'includes/db.php';

$user_id = (int)$_POST['user_id'];
$message = $_POST['message'];
$sender_type = $_POST['sender_type'];
$receiver_id = (int)$_POST['receiver_id'];

// Kiểm tra và gán receiver_id mặc định nếu không có
if (empty($receiver_id)) {
    die(json_encode(['status' => 'error', 'message' => 'Receiver ID is required']));
}

$sql = "INSERT INTO chat_messages (user_id, message, sender_type, sent_at, receiver_id) VALUES (?, ?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issi", $user_id, $message, $sender_type, $receiver_id);
if (!$stmt->execute()) {
    die(json_encode(['status' => 'error', 'message' => 'Failed to insert message']));
}

$options = array(
    'cluster' => 'ap1',
    'useTLS' => true
);
$pusher = new Pusher\Pusher(
    'ddf07516f2a5d2f90028',
    'bb793cc78fb16d544f31',
    '2002085',
    $options
);

$data = [
    'user_id' => $user_id,
    'receiver_id' => $receiver_id,
    'message' => $message,
    'sender_type' => $sender_type,
    'username' => $sender_type === 'user' ? getUsername($user_id, $conn) : 'Dược sĩ',
    'sent_at' => date('Y-m-d H:i:s')
];
$pusher->trigger('chat-channel', 'new-message', $data);

function getUsername($user_id, $conn) {
    $stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['username'] ?? 'Người dùng';
}

echo json_encode(['status' => 'success']);
?>