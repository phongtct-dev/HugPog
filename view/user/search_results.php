<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bộ lọc</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="container">
        <h1 class="my-4">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h1>
        <p><?php echo count($products); ?> sản phẩm được tìm thấy.</p>

        <div class="row">
            <?php if (!empty($products)): ?>
                <?php
                // Chia danh sách sản phẩm thành các nhóm 4 sản phẩm mỗi hàng
                $productBlocks = array_chunk($products, 4);
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
                            <div class="col-md-3 col-sm-6">
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

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>