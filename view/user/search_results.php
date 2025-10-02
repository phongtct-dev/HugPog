<?php
include __DIR__ . '/../header.php';
?>

<div class="container">
    <h1 class="my-4">Kết quả tìm kiếm cho: "<?php echo htmlspecialchars($keyword); ?>"</h1>
    <p><?php echo count($products); ?> sản phẩm được tìm thấy.</p>

    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm d-flex flex-column">
                        <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $product['id']; ?>">
                            <img class="card-img-top" src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </a>
                        <div class="card-body flex-grow-1">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $product['id']; ?>" class="text-decoration-none text-dark"><?php echo htmlspecialchars($product['name']); ?></a>
                            </h5>
                            <h4 class="card-price text-danger"><?php echo number_format($product['price'], 0, ',', '.'); ?> VNĐ</h4>
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
            <p class="text-center mt-5">Không tìm thấy sản phẩm nào phù hợp với từ khóa của bạn.</p>
        <?php endif; ?>
    </div>
</div>

<?php
include __DIR__ . '/../footer.php';
?>