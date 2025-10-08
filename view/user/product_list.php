<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <h3 class="my-4">Bộ lọc</h3>
                <form action="<?php echo BASE_URL; ?>public/product_list.php" method="GET">
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
                        <?php
                        // Chia danh sách sản phẩm thành các nhóm 4 sản phẩm mỗi hàng
                        $productBlocks = array_chunk($products, 3);
                        ?>

                        <?php foreach ($productBlocks as $block): ?>
                            <div class="row mb-4"> <!-- Một hàng gồm 4 sản phẩm -->
                                <?php foreach ($block as $p): ?>
                                    <?php
                                    $discountPercentage = $p['discount_percent'] ?? 0;
                                    $salePrice = $p['discounted_price'] ?? $p['price'];
                                    $imageUrl = htmlspecialchars($p['image_url'] ?? '../public/asset/image/no-image.png');
                                    $productName = htmlspecialchars($p['name'] ?? 'Sản phẩm không tên');
                                    $category = htmlspecialchars($p['category_name'] ?? 'Chưa phân loại');
                                    ?>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="product">
                                            <div class="product-img">
                                                <img src="<?= $imageUrl ?>" alt="<?= $productName ?>" class="img-fluid">
                                                <?php if ($discountPercentage > 0): ?>
                                                    <div class="product-label">
                                                        <span class="sale">-<?= $discountPercentage ?>%</span>
                                                        <span class="new">SALE</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category"><?= $category ?></p>
                                                <h3 class="product-name">
                                                    <a href="<?= BASE_URL ?>public/product.php?id=<?= $p['id'] ?>">
                                                        <?= $productName ?>
                                                    </a>
                                                </h3>
                                                <h4 class="product-price">
                                                    <?= number_format($salePrice, 0, ',', '.') ?> VNĐ
                                                    <?php if ($discountPercentage > 0): ?>
                                                        <del class="product-old-price"><?= number_format($p['price'], 0, ',', '.') ?> VNĐ</del>
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
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <div class="col-md-12">
                            <p class="text-center">Không tìm thấy sản phẩm nào.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');
        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                priceValue.textContent = new Intl.NumberFormat('vi-VN').format(this.value);
            });
        }
    </script>
    <script src="/HugPog/public/js/main.js"></script>

</body>

</html>