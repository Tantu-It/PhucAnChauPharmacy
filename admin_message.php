<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: auth/login.php");
    exit;
}
$username = $_SESSION['username'];
$role = $_SESSION['role'];
$admin_id = $_SESSION['user_id'];

// Lấy danh sách người dùng đã gửi tin nhắn
$sql_users = "SELECT DISTINCT u.id, u.username 
              FROM chat_messages cm 
              JOIN users u ON cm.user_id = u.id 
              WHERE cm.sender_type = 'user' AND cm.receiver_id = ?
              ORDER BY u.username ASC";
$stmt_users = $conn->prepare($sql_users);
$stmt_users->bind_param("i", $admin_id);
$stmt_users->execute();
$users = $stmt_users->get_result();

// Lấy tin nhắn của người dùng được chọn
$selected_user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : null;
$selected_username = null;
$messages = null;
if ($selected_user_id) {
    // Lấy username của người dùng được chọn
    $stmt_username = $conn->prepare("SELECT username FROM users WHERE id = ?");
    $stmt_username->bind_param("i", $selected_user_id);
    $stmt_username->execute();
    $result_username = $stmt_username->get_result();
    $selected_username = $result_username->fetch_assoc()['username'] ?? 'Người dùng';

    $sql_messages = "SELECT cm.*, u.username 
                     FROM chat_messages cm 
                     LEFT JOIN users u ON cm.user_id = u.id 
                     WHERE (cm.user_id = ? AND cm.receiver_id = ?) OR (cm.user_id = ? AND cm.receiver_id = ?)
                     ORDER BY cm.sent_at ASC";
    $stmt_messages = $conn->prepare($sql_messages);
    $stmt_messages->bind_param("iiii", $selected_user_id, $admin_id, $admin_id, $selected_user_id);
    $stmt_messages->execute();
    $messages = $stmt_messages->get_result();

    // Đánh dấu tin nhắn đã đọc
    $stmt_update = $conn->prepare("UPDATE chat_messages SET is_read = 1 WHERE user_id = ? AND receiver_id = ? AND sender_type = 'user'");
    $stmt_update->bind_param("ii", $selected_user_id, $admin_id);
    $stmt_update->execute();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Chat - Phúc An Châu</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="assets/css/message.css">
    <style>
        .chat-wrapper {
            display: flex;
            max-width: 1000px;
            margin: 20px auto;
        }
        .user-list {
            width: 30%;
            border-right: 1px solid #ccc;
            padding: 10px;
        }
        .user-list a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            background: #f1f1f1;
            text-decoration: none;
            color: #333;
        }
        .user-list a:hover {
            background: #ddd;
        }
        .chat-container {
            width: 70%;
            padding: 10px;
        }
    </style>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Nhà thuốc Phúc An Châu</title>
    <link rel="icon" href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>
    <div class="chat-wrapper">
        <div class="user-list">
            <h3>Danh sách người dùng</h3>
            <?php while ($user = $users->fetch_assoc()): ?>
                <a href="?user_id=<?php echo $user['id']; ?>">
                    <?php echo htmlspecialchars($user['username']); ?>
                </a>
            <?php endwhile; ?>
        </div>
        <div class="admessage-container">
            <?php if ($selected_user_id): ?>
                <h2>Chat với <?php echo htmlspecialchars($selected_username); ?></h2>
                <div class="message-box" id="chatBox">
                    <?php if ($messages): ?>
                        <?php while ($row = $messages->fetch_assoc()): ?>
                            <div class="message <?php echo $row['sender_type'] == 'user' ? 'received' : 'sent'; ?>">
                                <strong><?php echo htmlspecialchars($row['username'] ?: ($row['sender_type'] == 'user' ? $selected_username : 'Dược sĩ')); ?>:</strong>
                                <p><?php echo htmlspecialchars($row['message']); ?></p>
                                <span><?php echo date('H:i d/m/Y', strtotime($row['sent_at'])); ?></span>
                            </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>
                <form class="message-form" onsubmit="sendMessage(event)">
                    <input type="hidden" id="selectedUserId" value="<?php echo $selected_user_id; ?>">
                    <textarea id="messageInput" placeholder="Nhập phản hồi..." required></textarea>
                    <button type="submit">➤</button>
                </form>
            <?php else: ?>
                <h2>Chọn một người dùng để xem tin nhắn</h2>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>
    <script>
        const adminId = <?php echo $admin_id; ?>;
        const selectedUserId = <?php echo json_encode($selected_user_id); ?>;

        const pusher = new Pusher('ddf07516f2a5d2f90028', {
            cluster: 'ap1'
        });
        pusher.connection.bind('connected', function() {
            console.log('Pusher connected successfully!');
        });
        pusher.connection.bind('error', function(err) {
            console.error('Pusher error:', err);
        });

        const channel = pusher.subscribe('chat-channel');

        channel.bind('new-message', function(data) {
            console.log('Received message:', data); // Debugging
            if (selectedUserId && ((data.user_id == selectedUserId && data.receiver_id == adminId) || (data.user_id == adminId && data.receiver_id == selectedUserId))) {
                const chatBox = document.getElementById('chatBox');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${data.sender_type === 'user' ? 'received' : 'sent'}`;
                messageDiv.innerHTML = `
                    <strong>${data.username || (data.sender_type === 'user' ? 'Người dùng' : 'Dược sĩ')}:</strong>
                    <p>${data.message}</p>
                    <span>${new Date(data.sent_at).toLocaleString('vi-VN')}</span>
                `;
                chatBox.appendChild(messageDiv);
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });

        function sendMessage(event) {
            event.preventDefault();
            const messageInput = document.getElementById('messageInput');
            const message = messageInput.value.trim();
            if (message && selectedUserId) {
                fetch('send_message.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${adminId}&message=${encodeURIComponent(message)}&sender_type=pharmacist&receiver_id=${selectedUserId}`
                }).then(response => response.json()).then(data => {
                    if (data.status === 'success') {
                        messageInput.value = '';
                    } else {
                        console.error('Failed to send message:', data.message);
                    }
                }).catch(error => {
                    console.error('Error sending message:', error);
                });
            }
        }
    </script>
</body>
</html>