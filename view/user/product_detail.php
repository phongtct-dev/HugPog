<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($product['name'] ?? 'Chi tiết sản phẩm'); ?> — Chi tiết</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="container py-4">
        <?php if (!$product_found): ?>
            <div class="alert alert-warning">Sản phẩm không tìm thấy.</div>
        <?php else: ?>
            <div class="row">
                <div class="col-md-7 d-flex justify-content-center align-items-center">
                    <img src="<?php echo htmlspecialchars($product['image_url'] ?? ''); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-height: 400px; object-fit: contain;" />
                </div>
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h2>
                        <div>
                            <div class="product-rating">
                                <?php for ($i = 1; $i <= 5; $i++) { echo $i <= $product['average_rating'] ? '<i class="fa fa-star"></i>' : '<i class="fa fa-star-o"></i>'; } ?>
                            </div>
                            <a class="review-link" href="#reviews"><?php echo $product['review_count']; ?> Đánh giá | Thêm đánh giá</a>
                        </div>
                        <div>
                            <h3 class="product-price">
                                <?php echo number_format($product['final_price'], 0, ',', '.'); ?> VNĐ
                                <?php if ($product['discount'] > 0): ?>
                                    <del class="product-old-price"><?php echo number_format($product['old_price'], 0, ',', '.'); ?> VNĐ</del>
                                <?php endif; ?>
                            </h3>
                            <span class="product-available"><?php echo ($product['stock'] > 0) ? 'Còn hàng' : 'Hết hàng'; ?></span>
                        </div>
                        <p><?php echo htmlspecialchars(substr($product['description'] ?? 'Đang cập nhật...', 0, 300)) . '...'; ?></p>

                        <form class="add-to-cart" action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST" style="display: inline;">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            
                                <input type="number" class="form-control" name="quantity" value="1" min="1" max="<?php echo $product['stock'] ?? 99; ?>" style="width: 60px;">
                                <button class="add-to-cart-btn btn btn-danger" type="submit"><i class="fa fa-shopping-cart"></i> Thêm vào giỏ</button>
                            
                        </form>
                        <ul class="product-links mt-3">
                            <li>Danh mục:
                                <a href="#"><?php echo htmlspecialchars($product['category_name']); ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="product-tab" class="mt-4">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#desc-tab-pane" type="button">Mô tả</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews-tab-pane" type="button">Đánh giá (<?php echo $review_count; ?>)</button></li>
                </ul>
                <div class="tab-content p-3 border border-top-0" id="productTabContent">
                    <div class="tab-pane fade show active" id="desc-tab-pane" role="tabpanel"><?php echo nl2br(htmlspecialchars($product['description'] ?? 'Chưa có mô tả.')); ?></div>
                    <div class="tab-pane fade" id="reviews-tab-pane" role="tabpanel">
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Đánh giá từ khách hàng</h5>
                                <div class="reviews">
                                <?php if (!empty($reviews)): ?>
                                    <?php foreach ($reviews as $review): ?>
                                        <div class="d-flex mb-4">
                                            <div class="flex-shrink-0"><i class="fas fa-user-circle fa-3x text-secondary"></i></div>
                                            <div class="ms-3">
                                                <div class="fw-bold"><?php echo htmlspecialchars($review['username']); ?></div>
                                                <div class="text-warning">
                                                    <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
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
                            </div>
                            <div class="col-md-6">
                                <h5>Viết đánh giá</h5>
                                <?php if ($canReview): ?>
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
                                <?php else: ?>
                                    <div class="alert alert-info">Bạn cần mua sản phẩm này để có thể đánh giá.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Sản phẩm bán chạy</h3>
                    </div>
                </div>
                <?php if (!empty($top_products)): ?>
                    <?php foreach ($top_products as $tp): ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="product">
                                <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $tp['id'] ?? 0; ?>" class="product-link">
                                    <div class="product-img">
                                        <img src="<?php echo htmlspecialchars($tp['image_url'] ?? '../public/asset/image/no-image.png'); ?>" alt="<?php echo htmlspecialchars($tp['product_name'] ?? 'Sản phẩm'); ?>" class="img-fluid" style="max-height: 250px; object-fit: cover; width: 100%;">
                                        <?php if (!empty($tp['discount_percent']) && $tp['discount_percent'] > 0): ?>
                                            <div class="product-label">
                                                <span class="sale">-<?php echo $tp['discount_percent']; ?>%</span>
                                                <span class="new">SALE</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category"><?php echo htmlspecialchars($tp['category_name'] ?? 'Chưa phân loại'); ?></p>
                                        <h3 class="product-name"><?php echo htmlspecialchars($tp['product_name'] ?? $tp['name'] ?? 'Không rõ tên'); ?></h3>
                                        <h4 class="product-price">
                                            <?php echo formatPrice($tp['discounted_price'] ?? $tp['price'] ?? 0); ?>
                                            <?php if (!empty($tp['discount_percent']) && $tp['discount_percent'] > 0): ?>
                                                <del class="product-old-price"><?php echo formatPrice($tp['price']); ?></del>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                </a>
                                <form class="add-to-cart" action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $tp['id'] ?? 0; ?>">
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-center">Không tìm thấy sản phẩm bán chạy nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>
</html>