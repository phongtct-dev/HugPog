<?php
function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop01.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Laptop từ A->Z</h3>
                            <a href="https://thinkpro.vn/noi-dung/laptop-tu-a-z-huong-dan-su-dung-va-meo-toi-uu" class="cta-btn">Thông Tin <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop03.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Phụ kiện Laptop cần có</h3>
                            <a href="https://maytinhgiaphat.vn/linh-kien-may-tinh-huong-dan-chi-tiet-tu-a-z/" class="cta-btn">Thông Tin <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop02.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Tìm hiểu về Máy ảnh</h3>
                            <a href="https://viblo.asia/p/tim-hieu-ro-ve-may-anh-va-nhung-khai-niem-de-nham-lan-1VgZvp615Aw" class="cta-btn">Thông Tin<i class="fa fa-arrow-circle-right"></i></a>
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
                        <h3 class="title">Sản phẩm gần đây</h3>
                    </div>
                </div>

                <?php if (!empty($productBlocks)): ?>
                    <?php foreach ($productBlocks as $products): ?>
                        <?php foreach ($products as $p):
                            $discountPercentage = $p['discount_percent'] ?? 0;
                            $salePrice = $p['discounted_price'] ?? $p['price'];
                        ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="product">
                                    <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $p['id']; ?>" class="product-link">
                                        <div class="product-img">
                                            <img src="<?php echo htmlspecialchars($p['image_url'] ?? '../public/asset/image/no-image.png'); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                            <?php if ($discountPercentage > 0): ?>
                                                <div class="product-label">
                                                    <span class="sale">-<?php echo $discountPercentage; ?>%</span>
                                                    <span class="new">SALE</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category"><?php echo htmlspecialchars($p['category_name'] ?? 'Chưa phân loại'); ?></p>
                                            <h3 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                                            <h4 class="product-price">
                                                <?php echo formatPrice($salePrice); ?>
                                                <?php if ($discountPercentage > 0): ?>
                                                    <del class="product-old-price"><?php echo formatPrice($p['price']); ?></del>
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
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-center">Không tìm thấy sản phẩm nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    $endTime = strtotime("2025-10-15 23:59:59");
    $timeLeft = max($endTime - time(), 0);
    ?>
    <div id="hot-deal" class="text-center py-5 bg-light">
        <h2>Khuyến mãi hot tuần này</h2>
        <p>Giảm giá đến 10%</p>
        <a class="btn btn-danger" href="product_list.php">Mua ngay</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
    <script>
        // ... script countdown giữ nguyên ...
        let t = <?php echo $timeLeft; ?>;
        function c() { /* ... */ }
        c();
        setInterval(c, 1000);
    </script>
    
    <style>
        .product-link { display: block; color: inherit; text-decoration: none; }
        .product-link:hover { color: inherit; }
    </style>
</body>
</html>