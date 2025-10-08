<?php
// --- Helper functions (paste this BEFORE including product_detail.php) ---

if (!function_exists('format_currency')) {
    /**
     * Định dạng tiền VNĐ: 1.200.000 VNĐ
     * @param mixed $amount
     * @param int $decimals
     * @param string $suffix
     * @return string
     */
    function format_currency($amount, $decimals = 0, $suffix = ' VNĐ')
    {
        if (!is_numeric($amount)) $amount = 0;
        // Nếu amount là string có dấu phẩy/chấm, ép về float
        $num = (float) $amount;
        return number_format($num, $decimals, ',', '.') . $suffix;
    }
}

if (!function_exists('render_stars')) {
    /**
     * Sinh HTML hiển thị sao (FontAwesome 6)
     * @param float|int $rating (0..5)
     * @return string HTML
     */
    function render_stars($rating)
    {
        $rating = (float)$rating;
        if ($rating < 0) $rating = 0;
        if ($rating > 5) $rating = 5;

        $full = floor($rating);
        $half = (($rating - $full) >= 0.5);
        $html = '';

        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $full) {
                // full star
                $html .= '<i class="fa-solid fa-star" aria-hidden="true"></i> ';
            } elseif ($half && $i == $full + 1) {
                // half star
                $html .= '<i class="fa-solid fa-star-half-stroke" aria-hidden="true"></i> ';
            } else {
                // empty star (regular)
                $html .= '<i class="fa-regular fa-star" aria-hidden="true"></i> ';
            }
        }
        return $html;
    }
}
?>

<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($product['name']); ?> — Chi tiết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="container py-4">
        <?php if (!$product_found): ?>
            <div class="alert alert-warning">Sản phẩm không tìm thấy.</div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-7 d-flex justify-content-center align-items-center">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? '../path/to/default/image.png'); ?>"
                        class="img-fluid"
                        alt="<?php echo htmlspecialchars($product['name']); ?>"
                        style="max-height: 400px; object-fit: contain;" />
                </div>
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <div>
                            <div class="product-rating">
                                <?php echo render_stars($product['average_rating']); ?>
                            </div>
                            <a class="review-link" href="#tab3">
                                <?php echo $product['review_count']; ?> Đánh giá | Thêm đánh giá
                            </a>
                        </div>
                        <div>
                            <h3 class="product-price">
                                <?php echo format_currency($product['final_price']); ?>
                                <?php if ($product['discount'] > 0): ?>
                                    <del class="product-old-price"><?php echo format_currency($product['old_price']); ?></del>
                                <?php endif; ?>
                            </h3>
                            <span class="product-available">
                                <?php echo ($product['stock'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?>
                            </span>
                        </div>
                        <p><?php echo htmlspecialchars(substr($product['description'] ?? 'Đang cập nhật...', 0, 300)) . '...'; ?></p>

                        <form class="add-to-cart" action="add_to_cart.php" method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <input type="number" id="qty-input" name="quantity" value="1" min="1" max="<?php echo $product['stock'] ?? 99; ?>">
                            <button class="add-to-cart-btn" data-product-id="<?= $product['id'] ?>">
                                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                            </button>
                        </form>
                        <ul class="product-links">
                            <li>Danh mục:
                                <a href="product_list.php?category=<?php echo urlencode($product['category_name']); ?>">
                                    <?php echo htmlspecialchars($product['category_name']); ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tabs: mô tả / chi tiết / đánh giá -->
            <div class="mt-4">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc">Mô tả</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews">Đánh giá (<?php echo $review_count; ?>)</button></li>
                </ul>
                <div class="tab-content p-3 border border-top-0">
                    <div class="tab-pane active" id="desc">
                        <?php echo nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả.')); ?>
                    </div>
                    <div class="tab-pane" id="reviews">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Đánh giá từ khách hàng</h5>
                                <?php if (!empty($reviews)): ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <div class="d-flex mb-4">
                                            <div class="flex-shrink-0"><i class="fas fa-user-circle fa-3x text-secondary"></i></div>
                                            <div class="ms-3">
                                                <div class="fw-bold"><?php echo htmlspecialchars($review['username']); ?></div>
                                                <div class="text-warning">
                                                    <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; ?>
                                                    <?php for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
                                                </div>
                                                <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                                                <div class="small text-muted mt-1"><?php echo date('d/m/Y', strtotime($review['created_at'])); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <h5>Viết đánh giá</h5>

                                <div class="card bg-light">
                                    <div class="card-body">
                                        <form action="<?php echo BASE_URL; ?>public/submit_review.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label">Chấm điểm</label>
                                                <select name="rating" class="form-select" required>
                                                    <option value="">Chọn số sao</option>
                                                    <option value="5">5 sao (Tuyệt vời)</option>
                                                    <option value="4">4 sao (Tốt)</option>
                                                    <option value="3">3 sao (Bình thường)</option>
                                                    <option value="2">2 sao (Tệ)</option>
                                                    <option value="1">1 sao (Rất tệ)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Bình luận của bạn</label>
                                                <textarea class="form-control" name="comment" rows="4" required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="section-title">
                                <h3 class="title">Sản phẩm bán chạy</h3>
                            </div>
                        </div>

                        <?php if (!empty($productBlocks)): ?>
                            <?php $colIndex = 0; // Biến đếm cột (từ 0 đến 3) 
                            ?>
                            <?php foreach ($productBlocks as $products): ?>
                                <div class="col-md-3 col-sm-6">
                                    <div class="products-tabs">
                                        <div id="tab<?= $colIndex + 1 ?>" class="tab-pane active">
                                            <div class="products-slick" data-nav="#slick-nav-<?= $colIndex + 1 ?>">
                                                <?php foreach ($products as $p):
                                                    // Lấy thông tin giá và chiết khấu đã được Model tính toán
                                                    $discountPercentage = $p['discount_percent'] ?? 0;
                                                    $salePrice = $p['discounted_price'] ?? $p['price'];
                                                ?>
                                                    <div class="product">
                                                        <div class="product-img">
                                                            <img src="<?= htmlspecialchars($p['image_url'] ?? '../public/asset/image/no-image.png') ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                                                            <?php if ($discountPercentage > 0): ?>
                                                                <div class="product-label">
                                                                    <span class="sale">-<?= $discountPercentage ?>%</span>
                                                                    <span class="new">SALE</span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="product-body">
                                                            <p class="product-category"><?= htmlspecialchars($p['category_name'] ?? 'Chưa phân loại') ?></p>
                                                            <h3 class="product-name">
                                                                <a href="<?= BASE_URL ?>public/product.php?id=<?= $p['id'] ?>">
                                                                    <?= htmlspecialchars($p['name']) ?>
                                                                </a>
                                                            </h3>
                                                            <h4 class="product-price">
                                                                <?= formatPrice($salePrice) ?>
                                                                <?php if ($discountPercentage > 0): ?>
                                                                    <del class="product-old-price"><?= formatPrice($p['price']) ?></del>
                                                                <?php endif; ?>
                                                            </h4>
                                                        </div>
                                                        <form class="add-to-cart" action="<?= BASE_URL ?>public/add_to_cart.php" method="POST" style="display:inline;">
                                                            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
                                                            <button type="submit" class="add-to-cart-btn">
                                                                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                                            </button>
                                                        </form>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div id="slick-nav-<?= $colIndex + 1 ?>" class="products-slick-nav"></div>
                                        </div>
                                    </div>
                                </div>
                            <?php $colIndex++; // Tăng chỉ số cột
                            endforeach; ?>
                        <?php else: ?>
                            <div class="col-md-12">
                                <p class="text-center">Không tìm thấy sản phẩm bán chạy nào.</p>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>