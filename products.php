<?php
session_start();
include 'includes/db.php';

// Check if database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Initialize session variables
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Hàm lấy danh mục theo slug
function getCategoryBySlug($conn, $slug) {
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE slug = ?");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    return $stmt->get_result();
}

// Hàm lấy sản phẩm theo category_id với phân trang
function getProductsByCategoryId($conn, $category_id, $limit, $offset) {
    $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY id ASC LIMIT ? OFFSET ?");
    $stmt->bind_param("iii", $category_id, $limit, $offset);
    $stmt->execute();
    return $stmt->get_result();
}

// Hàm lấy tổng số sản phẩm theo category_id
function getTotalProductsByCategoryId($conn, $category_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE category_id = ?");
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['total'];
}

// Xử lý dữ liệu đầu vào
$category_slug = isset($_GET['category']) ? $_GET['category'] : '';
$limit = 8; // Số sản phẩm trên mỗi trang
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; // Ensure page is at least 1
$offset = ($page - 1) * $limit;

$products = [];
$category_name = "Tất cả sản phẩm";
$total_products = 0;

if ($category_slug !== '') {
    $category_result = getCategoryBySlug($conn, $category_slug);
    if ($category_result->num_rows === 1) {
        $category = $category_result->fetch_assoc();
        $category_id = $category['id'];
        $category_name = $category['name'];
        $products = getProductsByCategoryId($conn, $category_id, $limit, $offset);
        $total_products = getTotalProductsByCategoryId($conn, $category_id);
    } else {
        $error = "Không tìm thấy danh mục.";
    }
} else {
    $stmt = $conn->prepare("SELECT * FROM products ORDER BY id ASC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $limit, $offset);
    $stmt->execute();
    $products = $stmt->get_result();
    $total_result = $conn->query("SELECT COUNT(*) AS total FROM products");
    $total_products = $total_result->fetch_assoc()['total'];
}

$total_pages = ceil($total_products / $limit);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($category_name); ?> - Nhà thuốc</title>
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <style>
        h3 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 30px;
        }
        .矫 h4 {
            margin-bottom: 20px;
        }
        .pagination {
            margin-top: 20px;
            text-align: center;
        }
        .pagination a {
            margin: 0 5px;
            padding: 8px 12px;
            text-decoration: none;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .pagination a:hover {
            background-color: #f0f0f0;
        }
        .pagination .active {
            background-color: #007bff;
            color: white;
        }
        .pagination .disabled {
            color: #ccc;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>

    <main class="container">
        <h3 align="left"><?php echo htmlspecialchars($category_name); ?></h3>
        <section>
            <h4>Danh sách sản phẩm</h4>
            <p>Lưu ý: Thuốc kê đơn và một số sản phẩm sẽ cần tư vấn từ dược sĩ</p><br>

            <?php if (isset($error)): ?>
                <p class="alert"><?php echo htmlspecialchars($error); ?></p>
            <?php elseif ($products->num_rows > 0): ?>
                <div class="products-grid">
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <div class="product-card">
                            <img src="assets/img/<?php echo htmlspecialchars($row['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                 onerror="this.src='assets/img/placeholder.jpg'">
                            <?php if (isset($row['discount']) && $row['discount'] > 0): ?>
                                <span class="discount">-<?php echo $row['discount']; ?>%</span>
                            <?php endif; ?>
                            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                            <p class="price">
                                <?php if ($row['discount'] > 0): ?>
                                    <span class="original-price"><s><?php echo number_format($row['price']); ?> VNĐ</s></span>
                                    <span class="discounted-price"><?php echo number_format($row['final_price']); ?> VNĐ</span>
                                <?php else: ?>
                                    <?php echo number_format($row['price']); ?> VNĐ
                                <?php endif; ?>
                            </p>
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="choose-buy-btn">Chọn mua</a>
                        </div>
                    <?php endwhile; ?>
                </div>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?category=<?php echo urlencode($category_slug); ?>&page=<?php echo $page - 1; ?>">Previous</a>
                        <?php else: ?>
                            <a class="disabled">Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="?category=<?php echo urlencode($category_slug); ?>&page=<?php echo $i; ?>" 
                               <?php if ($i == $page): ?>class="active"<?php endif; ?>>
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                            <a href="?category=<?php echo urlencode($category_slug); ?>&page=<?php echo $page + 1; ?>">Next</a>
                        <?php else: ?>
                            <a class="disabled">Next</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <p>Không có sản phẩm nào.</p>
            <?php endif; ?>
        </section>
        <?php include 'includes/recently.php'; ?>
    </main>
    <?php include 'includes/footer.php'; ?>
</body>
</html>