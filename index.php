<?php
session_start();
include 'includes/db.php';


// Lấy thông tin người dùng
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$role = isset($_SESSION['role']) ? $_SESSION['role'] : '';

// Xử lý tìm kiếm
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_result = null;
$hot_products_result = null;
$featured_products_result = null;

if ($search_keyword !== '') {
    // Tìm kiếm không phân biệt hoa thường chỉ cho danh sách sản phẩm
    $stmt = $conn->prepare("SELECT * FROM products WHERE LOWER(name) LIKE LOWER(CONCAT('%', ?, '%')) ORDER BY id ASC");
    $stmt->bind_param("s", $search_keyword);
    $stmt->execute();
    $search_result = $stmt->get_result();
} else {
    // Mặc định nếu không tìm kiếm
    $sql = "SELECT * FROM products ORDER BY id ASC";
    $search_result = $conn->query($sql);
    if (!$search_result) {
        die("Lỗi truy vấn sản phẩm mới nhất: " . $conn->error);
    }
}

// Sản phẩm bán chạy không phụ thuộc tìm kiếm
$sql_hot = "SELECT * FROM products ORDER BY sold DESC LIMIT 12";
$hot_products_result = $conn->query($sql_hot);
if (!$hot_products_result) {
    die("Lỗi truy vấn sản phẩm bán chạy: " . $conn->error);
}

// Sản phẩm nổi bật không phụ thuộc tìm kiếm
$sql_featured = "SELECT * FROM products ORDER BY views DESC LIMIT 12";
$featured_products_result = $conn->query($sql_featured);
if (!$featured_products_result) {
    die("Lỗi truy vấn sản phẩm nổi bật: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phúc An Châu</title>
    <link rel="stylesheet" href="assets/css/Trang_chu.css">
    <link rel="icon" type="image/png"  href="assets/img/icon.jpg">
</head>

<body>
    <?php 
    include 'includes/header.php'; 
    include 'includes/nav.php';
    ?>


    <!-- Banner quảng cáo -->
    <section class="banner">
        <button class="carousel-prev" aria-label="Previous Slide">❮</button>
        <div class="banner-slideshow">
            <div class="banner-slide fade">
                <img src="assets/img/anh1.jpg" alt="Ảnh 1" loading="lazy" onerror="this.src='assets/img/placeholder.jpg'">
            </div>
            <div class="banner-slide ">
                <img src="assets/img/anh2.jpg" alt="Ảnh 2" loading="lazy" onerror="this.src='assets/img/placeholder.jpg'">
            </div>
            <div class="banner-slide ">
                <img src="assets/img/anh3.jpg" alt="ảnh 3" loading="lazy" onerror="this.src='assets/img/placeholder.jpg'">
            </div>
        </div>
        <button class="carousel-next" aria-label="Next Slide">❯</button>
    </section>


    

    <main class="container">

           <section class="banner-container">
<!-- Large Slideshow -->
        <div class="banner-large">
        <button class="carousel-prev" aria-label="Previous Slide">❮</button>
        <div class="slideshow">
        <div class="slideshow-slide active">
            <img src="assets/img/ads1.jpg" alt="Slide 1">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads2.jpg" alt="Slide 2">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads3.jpg" alt="Slide 3">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads4.jpg" alt="Slide 4">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads5.jpg" alt="Slide 5">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads6.jpg" alt="Slide 6">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads7.jpg" alt="Slide 7">
        </div>
        <div class="slideshow-slide">
            <img src="assets/img/ads8.jpg" alt="Slide 8">
        </div>
    </div>
    <button class="carousel-next" aria-label="Next Slide">❯</button>
    </div>
            <!-- Right Banners -->
            <div class="banner-right">
                <div class="banner-small top">
                    <img src="assets/img/1ads.jpg" alt="Hiểu về ung thư">
                </div>
                <div class="banner-small bottom">
                    <img src="assets/img/2ads.jpg" alt="Gói y tế 129K">
                </div>
            </div>
        </section>

        <div class="ads-image">
            <img src="assets/img/ads.jpg" alt="Quảng cáo">
        </div>

        <!-- Sản phẩm bán chạy (Custom Carousel) -->
        <section class="product-section ">
            <h2>Sản phẩm bán chạy</h2>
            <div class="carousel">
                <button class="carousel-prev" aria-label="Previous Slide">❮</button>
                <div class="carousel-wrapper">
                    <?php if ($hot_products_result && $hot_products_result->num_rows > 0): ?>
                        <?php
                        $products = [];
                        while ($row = $hot_products_result->fetch_assoc()) {
                            $products[] = $row;
                        }
                        $products_per_slide = 4;
                        $slides = array_chunk($products, $products_per_slide);
                        foreach ($slides as $index => $slide_products): ?>
                            <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="carousel-items">
                                    <?php foreach ($slide_products as $row): ?>
                                        <div class="carousel-item product-card">
                                            <img src="assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='assets/img/placeholder.jpg'" loading="lazy">
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
                                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn choose-buy-btn">Chọn mua</a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm bán chạy.</p>
                    <?php endif; ?>
                </div>
                <button class="carousel-next" aria-label="Next Slide">❯</button>
            </div>
        </section>

        <section class="category-section ">
            <h2>Danh mục sản phẩm</h2>
            <div class="categories-grid">
                <a href="products.php?category=thuoc" class="category-item">Thuốc</a>
                <a href="products.php?category=y-te" class="category-item">Thiết Bị Y Tế</a>
                <a href="products.php?category=my-pham" class="category-item">Mỹ Phẩm</a>
                <a href="products.php?category=tpcn" class="category-item">Thực Phẩm Chức Năng</a>
            </div>
        </section>

 

        <?php
        $limit = 8; // Số sản phẩm trên mỗi trang
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        // Nếu có tìm kiếm, sử dụng $search_result để tính phân trang
        if ($search_keyword !== '') {
            $total_products = $search_result->num_rows;
            $search_result->data_seek(0); // Reset con trỏ để sử dụng lại
            $result = $conn->query("SELECT * FROM products WHERE LOWER(name) LIKE LOWER('%$search_keyword%') ORDER BY id ASC LIMIT $limit OFFSET $offset");
        } else {
            $total_result = $conn->query("SELECT COUNT(*) AS total FROM products");
            $total_row = $total_result->fetch_assoc();
            $total_products = $total_row['total'];
            $result = $conn->query("SELECT * FROM products ORDER BY id ASC LIMIT $limit OFFSET $offset");
        }

        $total_pages = ceil($total_products / $limit);
        ?>

        <section class="product-section ">
            <h2>Danh sách sản phẩm</h2>
            <div class="products-grid">
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
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
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn choose-buy-btn">Chọn mua</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Không tìm thấy sản phẩm nào phù hợp với từ khóa "<?php echo htmlspecialchars($search_keyword); ?>".</p>
                <?php endif; ?>
            </div>

            <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $search_keyword ? '&search=' . urlencode($search_keyword) : ''; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
        </section>

   
        <section class="product-section">
            <h2>Sản phẩm nổi bật hôm nay</h2>
            <div class="carousel">
                <button class="carousel-prev">❮</button>
                <div class="carousel-wrapper">
                    <?php if ($featured_products_result && $featured_products_result->num_rows > 0): ?>
                        <?php
                        $products = [];
                        while ($row = $featured_products_result->fetch_assoc()) {
                            $products[] = $row;
                        }
                        $products_per_slide = 4;
                        $slides = array_chunk($products, $products_per_slide);
                        foreach ($slides as $index => $slide_products): ?>
                            <div class="carousel-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                <div class="carousel-items">
                                    <?php foreach ($slide_products as $row): ?>
                                        <div class="carousel-item product-card">
                                            <img src="assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='assets/img/placeholder.jpg'">
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
                                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn choose-buy-btn">Chọn mua</a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Không có sản phẩm nổi bật.</p>
                    <?php endif; ?>
                </div>
                <button class="carousel-next">❯</button>
            </div>
        </section>

        <!-- Góc sức khỏe -->
        <section class="health-section ">
            <h2>Góc sức khỏe</h2>
            <ul class="health-links">
                <li>
                    <img src="assets/img/summer.jpg" alt="Chăm sóc sức khỏe mùa hè" class="health-icon">
                    <h3>Mẹo chăm sóc sức khỏe mùa hè</h3>
                    <p>Bảo vệ sức khỏe trong thời tiết nắng nóng bằng các biện pháp đơn giản và hiệu quả.</p>
                    <a href="cham_soc_suc_khoe.php">Tìm hiểu thêm →</a>
                </li>
                <li>
                    <img src="assets/img/flu.jpg" alt="Phòng chống cảm cúm" class="health-icon">
                    <h3>Phòng chống cảm cúm hiệu quả</h3>
                    <p>Hướng dẫn tăng cường miễn dịch và phòng tránh cảm cúm theo mùa.</p>
                    <a href="cam_cum.php">Tìm hiểu thêm →</a>
                </li>
                <li>
                    <img src="assets/img/healthy.jpg" alt="Lối sống lành mạnh" class="health-icon">
                    <h3>Lối sống lành mạnh mỗi ngày</h3>
                    <p>Các thói quen tốt giúp bạn duy trì sức khỏe lâu dài và tinh thần sảng khoái.</p>
                    <a href="loisonglanhmanh.php">Tìm hiểu thêm →</a>
                </li>
            </ul>
        </section>

        <!-- Sản phẩm vừa xem -->
        <section class="recently-viewed ">
            <h2>Sản phẩm vừa xem</h2>
            <div class="products-grid">
                <?php
                if (isset($_SESSION['recently_viewed']) && count($_SESSION['recently_viewed']) > 0):
                    $recent_ids_array = array_reverse($_SESSION['recently_viewed']);
                    $recent_ids_array = array_values(array_unique($recent_ids_array));
                    $recent_ids_array = array_slice($recent_ids_array, 0, 4);
                    $recent_ids = implode(',', array_map('intval', $recent_ids_array));

                    if (!empty($recent_ids)):
                        $sql_recent = "SELECT * FROM products WHERE id IN ($recent_ids)";
                        $result_recent = $conn->query($sql_recent);

                        if ($result_recent):
                            while ($row = $result_recent->fetch_assoc()): ?>
                                <div class="product-card">
                                    <img src="assets/img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" onerror="this.src='assets/img/placeholder.jpg'">
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
                                    <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn choose-buy-btn">Chọn mua</a>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <p>Lỗi truy vấn sản phẩm vừa xem: <?php echo $conn->error; ?></p>
                        <?php endif;
                    else: ?>
                        <p>Chưa có sản phẩm vừa xem.</p>
                    <?php endif;
                else: ?>
                    <p>Chưa có sản phẩm vừa xem.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <?php include 'includes/footer.php' ?>
    <!-- Back to Top Button -->
    <button id="back-to-top" title="Về đầu trang">↑</button>

    <script src="assets/js/Trang_chu.js"></script>
    <script src="https://app.tudongchat.com/js/chatbox.js"></script>
    <script>
        const tudong_chatbox = new TuDongChat('TE0vRdwlziODYQ0E9tASu')
        tudong_chatbox.initial()
    </script>
</body>

</html>