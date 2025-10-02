<?php
// File: project/View/user/product_detail.php (Phiên bản Hoàn Chỉnh)

include __DIR__ . '/../header.php';
?>

<div class="container">
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <?php if ($product): // Bắt đầu kiểm tra nếu có sản phẩm ?>
        
        <div class="row">
            <div class="col-md-6">
                <img class="img-fluid rounded" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h1 class="display-5"><?php echo htmlspecialchars($product['name']); ?></h1>
                <h5 class="text-muted">Thương hiệu: <?php echo htmlspecialchars($product['brand']); ?></h5>
                <p><span class="badge bg-info"><?php echo htmlspecialchars($product['category_name']); ?></span></p>
                <h3 class="my-3 text-danger">
                    <?php if (isset($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                        <?php echo number_format($product['discounted_price'], 0, ',', '.'); ?> VNĐ
                        <small class="text-muted text-decoration-line-through ms-2" style="font-size: 0.6em;">
                            <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                        </small>
                        <span class="badge bg-danger ms-2">-<?php echo $product['discount_percent']; ?>%</span>
                    <?php else: ?>
                        <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                    <?php endif; ?>
                </h3>
                <p class="lead"><strong>Mô tả sản phẩm:</strong></p>
                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                <p><strong>Số lượng trong kho:</strong> <?php echo htmlspecialchars($product['stock']); ?></p>
                <hr>
                <form action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <div class="d-flex">
                        <input class="form-control text-center me-3" name="quantity" type="number" value="1" min="1" max="<?php echo $product['stock']; ?>" style="max-width: 5rem">
                        <button class="btn btn-primary flex-shrink-0" type="submit"><i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng</button>
                    </div>
                </form>
            </div>
        </div>
        
        <hr class="my-5">
        <div class="row">
            <div class="col-md-7">
                <h3>Đánh giá sản phẩm</h3>
                <?php if (empty($reviews)): ?>
                    <p>Chưa có đánh giá nào cho sản phẩm này.</p>
                <?php else: ?>
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
                <?php endif; ?>
            </div>
            
            <?php if ($canReview): ?>
            <div class="col-md-5">
                <h3>Viết đánh giá của bạn</h3>
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
            <?php endif; ?>
        </div>

    <?php else: // Trường hợp không tìm thấy sản phẩm ?>
        <div class="alert alert-warning text-center">Không tìm thấy sản phẩm!</div>
    <?php endif; // Kết thúc kiểm tra nếu có sản phẩm ?>
</div>

<?php
include __DIR__ . '/../footer.php';
?>