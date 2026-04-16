<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Lấy ID của dược sĩ (admin)
$adminStmt = $conn->prepare("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
$adminStmt->execute();
$adminResult = $adminStmt->get_result();
$admin_id = $adminResult->fetch_assoc()['id'] ?? null;

$sql = "SELECT cm.*, u.username 
        FROM chat_messages cm 
        LEFT JOIN users u ON cm.user_id = u.id 
        WHERE (cm.user_id = ? AND cm.receiver_id = ?) OR (cm.user_id = ? AND cm.receiver_id = ?)
        ORDER BY cm.sent_at ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiii", $user_id, $admin_id, $admin_id, $user_id);
$stmt->execute();
$messages = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat với Dược sĩ</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="assets/css/message.css">
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <section class="message-container">
        <h2>Chat với Dược sĩ</h2>
        <div class="message-box" id="chatBox">
            <?php while ($row = $messages->fetch_assoc()): ?>
                <div class="message <?php echo $row['sender_type'] == 'user' ? 'sent' : 'received'; ?>">
                    <strong><?php echo htmlspecialchars($row['username'] ?: 'Dược sĩ'); ?>:</strong>
                    <p><?php echo htmlspecialchars($row['message']); ?></p>
                    <span><?php echo date('H:i d/m/Y', strtotime($row['sent_at'])); ?></span>
                </div>
            <?php endwhile; ?>
        </div>
        <form class="message-form" onsubmit="sendMessage(event)">
            <textarea id="messageInput" placeholder="Nhập tin nhắn..." required></textarea>
            <button type="submit">Gửi</button>
        </form>
    </section>
    <?php include 'includes/footer.php' ?>
    <script>
        const userId = <?php echo $user_id; ?>;
        const adminId = <?php echo $admin_id; ?>;
        const username = '<?php echo htmlspecialchars($username); ?>';

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
            if (data.receiver_id === userId || (data.user_id === userId && data.receiver_id === adminId)) {
                const chatBox = document.getElementById('chatBox');
                const messageDiv = document.createElement('div');
                messageDiv.className = `message ${data.sender_type === 'user' ? 'sent' : 'received'}`;
                messageDiv.innerHTML = `
                    <strong>${data.username || (data.sender_type === 'user' ? username : 'Dược sĩ')}:</strong>
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
            if (message) {
                fetch('send_message.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `user_id=${userId}&message=${encodeURIComponent(message)}&sender_type=user&receiver_id=${adminId}`
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