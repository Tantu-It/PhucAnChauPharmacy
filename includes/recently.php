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