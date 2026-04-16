<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'includes/db.php';

// Kiểm tra đăng nhập và vai trò người dùng
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
$user_id = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

// Kiểm tra và xác thực product_id
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($product_id <= 0) {
    die('ID sản phẩm không hợp lệ.');
}

// Tăng lượt xem sản phẩm
$stmt_update_view = $conn->prepare("UPDATE products SET views = views + 1 WHERE id = ?");
$stmt_update_view->bind_param("i", $product_id);
$stmt_update_view->execute();
$stmt_update_view->close();

// Lấy thông tin sản phẩm
$stmt_product = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt_product->bind_param("i", $product_id);
$stmt_product->execute();
$result = $stmt_product->get_result();
if ($result->num_rows === 0) {
    die('Sản phẩm không tồn tại.');
}
$product = $result->fetch_assoc();
$stmt_product->close();

// Quản lý sản phẩm đã xem gần đây
if (!isset($_SESSION['recently_viewed'])) {
    $_SESSION['recently_viewed'] = [];
}
$_SESSION['recently_viewed'] = array_diff($_SESSION['recently_viewed'], [$product_id]);
array_unshift($_SESSION['recently_viewed'], $product_id);
$_SESSION['recently_viewed'] = array_slice($_SESSION['recently_viewed'], 0, 6);

// Lấy danh sách sản phẩm đã xem gần đây
$recent_products = [];
if (!empty($_SESSION['recently_viewed'])) {
    $recent_ids = implode(',', array_map('intval', $_SESSION['recently_viewed']));
    $stmt_recent = $conn->prepare("SELECT id, name, price, image, discount, final_price FROM products WHERE id IN ($recent_ids) AND id != ? LIMIT 5");
    $stmt_recent->bind_param("i", $product_id);
    $stmt_recent->execute();
    $recent_result = $stmt_recent->get_result();
    while ($row = $recent_result->fetch_assoc()) {
        $recent_products[] = $row;
    }
    $stmt_recent->close();
}

// Hàm xử lý bình luận
function handleComment($conn, $product_id, $user_id) {
    $comment_error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_comment'])) {
        if (!$user_id) {
            return "Vui lòng đăng nhập để bình luận!";
        }
        $comment_text = trim($_POST['comment']);
        $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;
        if (empty($comment_text)) {
            return "Nội dung bình luận không được để trống!";
        }
        $stmt = $conn->prepare("INSERT INTO comments (user_id, product_id, comment, parent_id) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            return "Lỗi prepare truy vấn thêm bình luận: " . $conn->error;
        }
        $stmt->bind_param("iisi", $user_id, $product_id, $comment_text, $parent_id);
        if ($stmt->execute()) {
            header("Location: product_detail.php?id=" . $product_id);
            exit;
        }
        $comment_error = "Lỗi khi thêm bình luận: " . $conn->error;
        $stmt->close();
    }
    return $comment_error;
}

// Hàm xử lý lượt thích bình luận
function handleCommentLike($conn, $product_id, $user_id) {
    $comment_error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like_comment'])) {
        if (!$user_id) {
            return "Vui lòng đăng nhập để thích bình luận!";
        }
        $comment_id = (int)$_POST['comment_id'];
        $check_like = $conn->prepare("SELECT * FROM comment_likes WHERE comment_id = ? AND user_id = ?");
        if ($check_like === false) {
            return "Lỗi prepare truy vấn kiểm tra lượt thích: " . $conn->error;
        }
        $check_like->bind_param("ii", $comment_id, $user_id);
        $check_like->execute();
        if ($check_like->get_result()->num_rows == 0) {
            $stmt = $conn->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (?, ?)");
            if ($stmt === false) {
                return "Lỗi prepare truy vấn thêm lượt thích: " . $conn->error;
            }
            $stmt->bind_param("ii", $comment_id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: product_detail.php?id=" . $product_id);
        exit;
    }
    return $comment_error;
}

// Xử lý bình luận và lượt thích
$comment_error = handleComment($conn, $product_id, $user_id) ?: handleCommentLike($conn, $product_id, $user_id);

// Lấy tổng số bình luận
$stmt_total = $conn->prepare("SELECT COUNT(*) as total FROM comments WHERE product_id = ?");
$stmt_total->bind_param("i", $product_id);
$stmt_total->execute();
$total_comments = $stmt_total->get_result()->fetch_assoc()['total'];
$stmt_total->close();

// Phân trang bình luận
$comments_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $comments_per_page;
$total_pages = ceil($total_comments / $comments_per_page);

// Lấy danh sách bình luận
$comments = [];
$stmt_comments = $conn->prepare("
    SELECT c.id, c.comment, c.created_at, c.parent_id, u.username, u.avatar 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.product_id = ? AND c.parent_id IS NULL 
    ORDER BY c.created_at DESC 
    LIMIT ? OFFSET ?");
if ($stmt_comments === false) {
    $comment_error = "Lỗi prepare truy vấn lấy bình luận: " . $conn->error;
} else {
    $stmt_comments->bind_param("iii", $product_id, $comments_per_page, $offset);
    $stmt_comments->execute();
    $result_comments = $stmt_comments->get_result();
    while ($row = $result_comments->fetch_assoc()) {
        // Lấy số lượt thích
        $stmt_likes = $conn->prepare("SELECT COUNT(*) as like_count FROM comment_likes WHERE comment_id = ?");
        $stmt_likes->bind_param("i", $row['id']);
        $stmt_likes->execute();
        $row['like_count'] = $stmt_likes->get_result()->fetch_assoc()['like_count'];
        $stmt_likes->close();

        // Kiểm tra người dùng đã thích chưa
        $row['is_liked'] = false;
        if ($user_id) {
            $check_user_like = $conn->prepare("SELECT * FROM comment_likes WHERE comment_id = ? AND user_id = ?");
            $check_user_like->bind_param("ii", $row['id'], $user_id);
            $check_user_like->execute();
            $row['is_liked'] = $check_user_like->get_result()->num_rows > 0;
            $check_user_like->close();
        }

        // Lấy phản hồi (replies)
        $stmt_replies = $conn->prepare("
            SELECT c.id, c.comment, c.created_at, u.username, u.avatar 
            FROM comments c 
            JOIN users u ON c.user_id = u.id 
            WHERE c.parent_id = ? 
            ORDER BY c.created_at ASC");
        $stmt_replies->bind_param("i", $row['id']);
        $stmt_replies->execute();
        $replies_result = $stmt_replies->get_result();
        $row['replies'] = [];
        while ($reply = $replies_result->fetch_assoc()) {
            $stmt_likes = $conn->prepare("SELECT COUNT(*) as like_count FROM comment_likes WHERE comment_id = ?");
            $stmt_likes->bind_param("i", $reply['id']);
            $stmt_likes->execute();
            $reply['like_count'] = $stmt_likes->get_result()->fetch_assoc()['like_count'];
            $stmt_likes->close();

            $reply['is_liked'] = false;
            if ($user_id) {
                $check_user_like = $conn->prepare("SELECT * FROM comment_likes WHERE comment_id = ? AND user_id = ?");
                $check_user_like->bind_param("ii", $reply['id'], $user_id);
                $check_user_like->execute();
                $reply['is_liked'] = $check_user_like->get_result()->num_rows > 0;
                $check_user_like->close();
            }
            $row['replies'][] = $reply;
        }
        $stmt_replies->close();
        $comments[] = $row;
    }
    $stmt_comments->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Chi tiết sản phẩm</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="stylesheet" href="assets/css/product-detail.css">
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <main class="container">
        <div class="product-detail-container">
            <div class="product-left">
                <div class="product-image">
                    <img src="assets/img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='assets/img/placeholder.jpg'">
                </div>
                <div class="product-info">
                    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                    <p class="price">
                        <?php if ($product['discount'] > 0): ?>
                            <span class="original-price"><s><?php echo number_format($product['price']); ?> VNĐ</s></span>
                            <span class="discounted-price"><?php echo number_format($product['final_price']); ?> VNĐ</span>
                        <?php else: ?>
                            <?php echo number_format($product['price']); ?> VNĐ
                        <?php endif; ?>
                    </p>
                    <p class="views">Lượt xem: <?php echo (int)$product['views'] + 1; ?></p>
                    <p>Mô tả: <?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
            </div>
            <div class="product-right" align="center">
                <div class="price-box">
                    <?php if ($product['discount'] > 0): ?>
                        <span class="original-price"><s><?php echo number_format($product['price']); ?> VNĐ</s></span>
                        <span class="discounted-price"><?php echo number_format($product['final_price']); ?> VNĐ</span>
                    <?php else: ?>
                        <?php echo number_format($product['price']); ?> VNĐ
                    <?php endif; ?>
                </div>
                <form method="POST" action="add_to_cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="quantity-control">
                        <button type="button" onclick="updateQuantity(this, -1)">-</button>
                        <input type="number" name="quantity" value="1" min="1" max="99" class="quantity-input">
                        <button type="button" onclick="updateQuantity(this, 1)">+</button>
                    </div>
                    <button type="submit" class="add-to-cart-btn">Thêm vào giỏ</button>
                </form>
            </div>
        </div>

        <div class="product-tabs">
            <div class="tabs-container">
                <div class="tabs">
                    <div class="tab active" onclick="openTab('ingredient')">Thành phần</div>
                    <div class="tab" onclick="openTab('usage')">Công dụng</div>
                    <div class="tab" onclick="openTab('instructions')">Cách dùng</div>
                    <div class="tab" onclick="openTab('side_effects')">Tác dụng phụ</div>
                    <div class="tab" onclick="openTab('precautions')">Lưu ý</div>
                    <div class="tab" onclick="openTab('storage')">Bảo quản</div>
                </div>
            </div>
            <div class="tab-content-container">
                <div id="ingredient" class="tab-content active">
                    <p><?php echo nl2br(htmlspecialchars($product['ingredient'] ?? 'Đang cập nhật...')); ?></p>
                </div>
                <div id="usage" class="tab-content">
                    <p><?php echo nl2br(htmlspecialchars($product['uses'] ?? 'Đang cập nhật...')); ?></p>
                </div>
                <div id="instructions" class="tab-content">
                    <p><?php echo nl2br(htmlspecialchars($product['usage'] ?? 'Đang cập nhật...')); ?></p>
                </div>
                <div id="side_effects" class="tab-content">
                    <p><?php echo nl2br(htmlspecialchars($product['side_effects'] ?? 'Đang cập nhật...')); ?></p>
                </div>
                <div id="precautions" class="tab-content">
                    <p><?php echo nl2br(htmlspecialchars($product['warning'] ?? 'Đang cập nhật...')); ?></p>
                </div>
                <div id="storage" class="tab-content">
                    <p><?php echo nl2br(htmlspecialchars($product['storage'] ?? 'Đang cập nhật...')); ?></p>
                </div>
            </div>
        </div>

        <div class="comments-section">
            <h2>Bình luận (<?php echo $total_comments; ?>)</h2>
            <div class="comment-container">
                <button class="comment-btn" onclick="toggleCommentForm()">Gửi đánh giá</button>
                <form method="POST" class="comment-form" style="display: none;">
                    <div class="comment-input-container">
                        <textarea name="comment" placeholder="Viết bình luận của bạn..." required></textarea>
                        <button type="submit" name="submit_comment" class="comment-btn">Gửi bình luận</button>
                    </div>
                </form>
            </div>

            <?php if ($comment_error): ?>
                <div class="comment-error"><?php echo htmlspecialchars($comment_error); ?></div>
            <?php endif; ?>

            <div class="comments-list">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment-item <?php echo ($user_id && $user_id == $comment['user_id']) ? 'current-user' : ''; ?>">
                        <div class="comment-header">
                            <img src="assets/img/<?php echo htmlspecialchars($comment['avatar'] ?? 'default.jpg'); ?>" alt="Avatar" class="comment-avatar">
                            <div class="comment-meta">
                                <p class="comment-author"><?php echo htmlspecialchars($comment['username'] ?? 'Khách'); ?></p>
                                <p class="comment-date"><?php echo date('d/m/Y H:i', strtotime($comment['created_at'])); ?></p>
                            </div>
                        </div>
                        <p class="comment-content"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></p>
                        <div class="comment-actions">
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="comment_id" value="<?php echo (int)$comment['id']; ?>">
                                <button type="submit" name="like_comment" class="like-btn <?php echo $comment['is_liked'] ? 'liked' : ''; ?>">
                                    <span class="emoji">👍</span> <span class="like-count"><?php echo $comment['like_count']; ?></span>
                                </button>
                            </form>
                            <button class="reply-btn" onclick="showReplyForm(<?php echo (int)$comment['id']; ?>)"><span class="emoji">💬</span> Trả lời</button>
                        </div>
                        <div id="reply-form-<?php echo (int)$comment['id']; ?>" class="reply-form" style="display:none;">
                            <div class="reply-header">
                                <img src="assets/avatar/<?php echo htmlspecialchars($_SESSION['user']['avatar'] ?? 'default.jpg'); ?>" alt="Avatar" class="comment-avatar">
                                <p class="comment-author"><?php echo htmlspecialchars($_SESSION['user']['username'] ?? 'bạn'); ?></p>
                            </div>
                            <form method="POST">
                                <input type="hidden" name="parent_id" value="<?php echo (int)$comment['id']; ?>">
                                <div class="reply-input-container">
                                    <textarea name="comment" placeholder="Viết phản hồi của bạn..." required></textarea>
                                    <button type="submit" name="submit_comment" class="reply-submit-btn"><span class="emoji">📤</span> Gửi</button>
                                </div>
                            </form>
                        </div>
                        <?php if (!empty($comment['replies'])): ?>
                            <div class="replies">
                                <?php foreach ($comment['replies'] as $reply): ?>
                                    <div class="comment-item reply <?php echo ($user_id && $user_id == $reply['user_id']) ? 'current-user' : ''; ?>">
                                        <div class="comment-header">
                                            <img src="assets/avatar/<?php echo htmlspecialchars($reply['avatar'] ?? 'default.jpg'); ?>" alt="Avatar" class="comment-avatar">
                                            <div class="comment-meta">
                                                <p class="comment-author"><?php echo htmlspecialchars($reply['username'] ?? 'Khách'); ?></p>
                                                <p class="comment-date"><?php echo date('d/m/Y H:i', strtotime($reply['created_at'])); ?></p>
                                            </div>
                                        </div>
                                        <p class="comment-content"><?php echo nl2br(htmlspecialchars($reply['comment'])); ?></p>
                                        <div class="comment-actions">
                                            <form method="POST" style="display:inline;">
                                                <input type="hidden" name="comment_id" value="<?php echo (int)$reply['id']; ?>">
                                                <button type="submit" name="like_comment" class="like-btn <?php echo $reply['is_liked'] ? 'liked' : ''; ?>">
                                                    <span class="emoji">👍</span> <span class="like-count"><?php echo $reply['like_count']; ?></span>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (!$user_id): ?>
                <p class="comment-login"><a href="auth/login.php">Đăng nhập</a> để bình luận.</p>
            <?php endif; ?>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?id=<?php echo $product_id; ?>&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php include 'includes/recently.php'; ?>
    </main>
    <?php include 'includes/footer.php'; ?>

    <script>
        function updateQuantity(button, change) {
            const input = button.parentElement.querySelector('.quantity-input');
            let value = parseInt(input.value) + change;
            value = Math.max(1, Math.min(99, value));
            input.value = value;
        }

        function openTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            document.querySelector(`.tab[onclick="openTab('${tabName}')"]`).classList.add('active');
        }

        function showReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            if (replyForm) {
                replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
            }
        }

        function toggleCommentForm() {
            const commentForm = document.querySelector('.comment-form');
            commentForm.style.display = commentForm.style.display === 'none' || commentForm.style.display === '' ? 'block' : 'none';
        }

        // Tự động mở tab đầu tiên khi tải trang
        document.addEventListener('DOMContentLoaded', () => openTab('ingredient'));
    </script>

    <?php ob_end_flush(); ?>
</body>
</html>