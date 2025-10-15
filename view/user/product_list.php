<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body">
                        <h4 class="fw-bold text-danger mb-4 border-bottom pb-2">
                            <i class="bi bi-funnel me-2"></i>Bộ lọc
                        </h4>

                        <form action="<?php echo BASE_URL; ?>public/product_list.php" method="GET">

                            <!-- Danh mục -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-secondary mb-2">
                                    <i class="bi bi-list-ul me-1"></i>Danh mục
                                </h6>
                                <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto;">
                                    <?php foreach ($categories as $category): ?>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox"
                                                name="categories[]"
                                                value="<?php echo $category['id']; ?>"
                                                id="cat_<?php echo $category['id']; ?>"
                                                <?php if (isset($filters) && in_array($category['id'], $filters['categories'])) echo 'checked'; ?>>
                                            <label class="form-check-label" for="cat_<?php echo $category['id']; ?>">
                                                <?php echo htmlspecialchars($category['name']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Giá -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-secondary mb-2">
                                    <i class="bi bi-cash-coin me-1"></i>Khoảng giá
                                </h6>
                                <div class="border rounded p-3">
                                    <label for="priceRange" class="form-label small">Tối đa:
                                        <span id="priceValue" class="fw-bold text-danger">
                                            <?php echo number_format($filters['max_price'] ?? 20000000); ?>
                                        </span> VNĐ
                                    </label>
                                    <input type="range" class="form-range"
                                        min="100000" max="20000000" step="100000"
                                        name="max_price" id="priceRange"
                                        value="<?php echo $filters['max_price'] ?? 20000000; ?>">
                                </div>
                            </div>

                            <!-- Thương hiệu -->
                            <div class="mb-4">
                                <h6 class="fw-semibold text-secondary mb-2">
                                    <i class="bi bi-tags me-1"></i>Thương hiệu
                                </h6>
                                <div class="border rounded p-2" style="max-height: 180px; overflow-y: auto;">
                                    <?php foreach ($brands as $brand): ?>
                                        <div class="form-check mb-1">
                                            <input class="form-check-input" type="checkbox"
                                                name="brands[]"
                                                value="<?php echo htmlspecialchars($brand['brand']); ?>"
                                                id="brand_<?php echo htmlspecialchars($brand['brand']); ?>"
                                                <?php if (isset($filters) && in_array($brand['brand'], $filters['brands'])) echo 'checked'; ?>>
                                            <label class="form-check-label" for="brand_<?php echo htmlspecialchars($brand['brand']); ?>">
                                                <?php echo htmlspecialchars($brand['brand']); ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <!-- Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger fw-semibold py-2 rounded-pill">
                                    <i class="bi bi-filter-circle me-1"></i>Áp dụng bộ lọc
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>


            <div class="col-lg-9">
                <h1 class="my-4">Danh sách sản phẩm</h1>
                <div class="row">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <?php
                            $discountPercentage = $p['discount_percent'] ?? 0;
                            $salePrice = $p['discounted_price'] ?? $p['price'];
                            $imageUrl = htmlspecialchars($p['image_url'] ?? '../public/asset/image/no-image.png');
                            $productName = htmlspecialchars($p['name'] ?? 'Sản phẩm không tên');
                            $category = htmlspecialchars($p['category_name'] ?? 'Chưa phân loại');
                            ?>
                            <div class="col-md-4 col-sm-6 mb-4">
                                <div class="product">
                                    <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $p['id']; ?>" class="product-link">
                                        <div class="product-img">
                                            <img src="<?php echo $imageUrl; ?>" alt="<?php echo $productName; ?>" class="img-fluid">
                                            <?php if ($discountPercentage > 0): ?>
                                                <div class="product-label">
                                                    <span class="sale">-<?php echo $discountPercentage; ?>%</span>
                                                    <span class="new">SALE</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category"><?php echo $category; ?></p>
                                            <h3 class="product-name"><?php echo $productName; ?></h3>
                                            <h4 class="product-price">
                                                <?php echo number_format($salePrice, 0, ',', '.'); ?> VNĐ
                                                <?php if ($discountPercentage > 0): ?>
                                                    <del class="product-old-price"><?php echo number_format($p['price'], 0, ',', '.'); ?> VNĐ</del>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                    </a>
                                    <form class="add-to-cart" action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="add-to-cart-btn">
                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="col-12 mt-4">
                            <nav aria-label="Page navigation">
                                <?php if ($totalPages > 1): ?>
                                    <ul class="pagination justify-content-center mb-0">
                                        <?php
                                        // Tạo query string từ các bộ lọc hiện tại
                                        $queryString = http_build_query(array_merge($filters, ['page' => '']));
                                        ?>

                                        <!-- Nút Trước -->
                                        <li class="page-item <?php echo ($currentPage <= 1) ? 'disabled' : ''; ?>">
                                            <a class="page-link text-danger fw-semibold border-0"
                                                href="?<?php echo http_build_query(array_merge($filters, ['page' => $currentPage - 1])); ?>">
                                                <i class="bi bi-chevron-left"></i> Trước
                                            </a>
                                        </li>

                                        <!-- Số trang -->
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                                <a class="page-link fw-semibold <?php echo ($i == $currentPage) ? 'bg-danger border-0' : 'text-danger border-0'; ?>"
                                                    href="?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>

                                        <!-- Nút Sau -->
                                        <li class="page-item <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>">
                                            <a class="page-link text-danger fw-semibold border-0"
                                                href="?<?php echo http_build_query(array_merge($filters, ['page' => $currentPage + 1])); ?>">
                                                Sau <i class="bi bi-chevron-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                <?php endif; ?>
                            </nav>
                        </div>

                    <?php else: ?>
                        <div class="col-md-12">
                            <p class="text-center">Không tìm thấy sản phẩm nào.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div> <?php include __DIR__ . '/../layout/footer.php'; ?>

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
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>

    <style>
        .product-link {
            display: block;
            color: inherit;
            text-decoration: none;
        }

        .product-link:hover {
            color: inherit;
        }
    </style>
</body>

</html>