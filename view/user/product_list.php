<?php
include __DIR__ . '/../header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-lg-3">
            <h3 class="my-4">Bộ lọc</h3>
            <form action="<?php echo BASE_URL; ?>public/index.php" method="GET">
                <div class="card mb-3">
                    <div class="card-header fw-bold">Danh mục</div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($categories as $category): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $category['id']; ?>" id="cat_<?php echo $category['id']; ?>"
                                    <?php if (in_array($category['id'], $filters['categories'])) echo 'checked'; ?>>
                                <label class="form-check-label" for="cat_<?php echo $category['id']; ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header fw-bold">Giá</div>
                    <div class="card-body">
                        <label for="priceRange" class="form-label">Tối đa: <span id="priceValue" class="fw-bold"><?php echo number_format($filters['max_price'] ?? 20000000); ?></span> VNĐ</label>
                        <input type="range" class="form-range" min="100000" max="20000000" step="100000" name="max_price" id="priceRange" 
                               value="<?php echo $filters['max_price'] ?? 20000000; ?>">
                    </div>
                </div>
                
                <div class="card mb-3">
                    <div class="card-header fw-bold">Thương hiệu</div>
                    <div class="card-body" style="max-height: 200px; overflow-y: auto;">
                        <?php foreach ($brands as $brand): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="brands[]" value="<?php echo htmlspecialchars($brand['brand']); ?>" id="brand_<?php echo htmlspecialchars($brand['brand']); ?>"
                                    <?php if (in_array($brand['brand'], $filters['brands'])) echo 'checked'; ?>>
                                <label class="form-check-label" for="brand_<?php echo htmlspecialchars($brand['brand']); ?>">
                                    <?php echo htmlspecialchars($brand['brand']); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="d-grid">
                    <button type="submit" class="btn btn-danger">Áp dụng bộ lọc</button>
                </div>
            </form>
        </div>

        <div class="col-lg-9">
            <h1 class="my-4">Danh sách sản phẩm</h1>
            <div class="row">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $product['id']; ?>">
                                    <img class="card-img-top" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                                </a>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($product['name']); ?></a>
                                    </h5>
                                    <h4 class="card-price text-danger">
                                        <?php if (isset($product['discount_percent']) && $product['discount_percent'] > 0): ?>
                                            <?php echo number_format($product['discounted_price'], 0, ',', '.'); ?> VNĐ
                                            <small class="text-muted text-decoration-line-through" style="font-size: 0.7em;"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</small>
                                        <?php else: ?>
                                            <?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                     <form action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST" class="d-grid">
                                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                        <button type="submit" class="btn btn-primary"><i class="fas fa-cart-plus"></i> Thêm vào giỏ</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center mt-5">Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    const priceRange = document.getElementById('priceRange');
    const priceValue = document.getElementById('priceValue');
    if(priceRange && priceValue) {
        priceRange.addEventListener('input', function() {
            priceValue.textContent = new Intl.NumberFormat('vi-VN').format(this.value);
        });
    }
</script>

<?php
include __DIR__ . '/../footer.php';
?>