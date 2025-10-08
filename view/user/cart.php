<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="container py-5">
        <div class="d-flex justify-content-between mb-4">
            <h2>Giỏ hàng của bạn (<?= count($cartItems) ?> sản phẩm)</h2>
        </div>

        <?= $message // Hiển thị thông báo cập nhật 
        ?>

        <?php if (empty($cartItems)): ?>
            <div class="alert alert-info text-center" role="alert">
                Giỏ hàng của bạn hiện đang trống. <a href="product_list.php" class="alert-link">Tiếp tục mua sắm.</a>
            </div>
        <?php else: ?>
            <div class="card shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <form action="cart.php" method="POST">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Sản phẩm</th>
                                    <th scope="col">Giá</th>
                                    <th scope="col">Số lượng</th>
                                    <th scope="col">Tổng</th>
                                    <th scope="col">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item):
                                    $product_detail_link = "product.php?id=" . $item['id'];
                                    // Giả định 'image' hoặc 'image_url' là trường lưu đường dẫn ảnh
                                    $image_path = htmlspecialchars($item['image'] ?? $item['image_url'] ?? '../public/asset/image/placeholder.png');
                                ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img
                                                    src="<?= $image_path ?>"
                                                    alt="<?= htmlspecialchars($item['name']) ?>"
                                                    width="60"
                                                    class="me-3 rounded" />
                                                <a href="<?= $product_detail_link ?>">
                                                    <span><?= htmlspecialchars($item['name']) ?></span>
                                                </a>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            // Giả định: price_original là giá gốc, price_final là giá đã giảm (nếu có)
                                            // Nếu không có giá đã giảm, dùng giá gốc.
                                            $price_to_use = $item['price_final'] ?? $item['price_original'];
                                            ?>

                                            <span class="product-price">
                                                <?= formatPrice($price_to_use) ?>
                                            </span>

                                            <?php if (isset($item['discount']) && $item['discount'] > 0): ?>
                                                <del class="product-old-price" style="font-size: 0.8em; display: block;">
                                                    <?= formatPrice($item['price_original']) ?>
                                                </del>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <input
                                                type="number"
                                                name="quantities[<?= $item['id'] ?>]" class="form-control form-control-sm"
                                                value="<?= $item['quantity'] ?>"
                                                min="1"
                                                style="width: 70px" />
                                        </td>
                                        <td>
                                            <?php
                                            // Tính tổng tiền cho sản phẩm này: total = price_final * quantity
                                            $item_total = $price_to_use * $item['quantity'];
                                            ?>
                                            <?= formatPrice($item_total) ?>
                                        </td>
                                        <td>
                                            <form action="cart.php" method="POST" style="display:inline;">
                                                <input type="hidden" name="product_id" value="<?= $item['id'] ?>">
                                                <button type="submit" name="remove_item" class="btn btn-sm btn-outline-danger">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end mb-3">
                            <button type="submit" name="update_cart" class="btn btn-primary me-2">Cập nhật Giỏ hàng</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <a href="index.php" class="btn btn-outline-secondary">Tiếp tục mua hàng</a>

                    <div class="card shadow-sm p-3 mt-3">
                        <h5 class="mb-3">Mã giảm giá (Voucher)</h5>
                        <?php if (isset($_SESSION['voucher'])): ?>
                            <div class="alert alert-success d-flex justify-content-between align-items-center">
                                <span>Mã <?= htmlspecialchars($_SESSION['voucher']['code']) ?> đã áp dụng!</span>
                                <form action="cart.php" method="POST" style="display:inline;">
                                    <button type="submit" name="remove_voucher" class="btn btn-sm btn-danger">Hủy</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form action="cart.php" method="POST">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="voucher_code" placeholder="Nhập mã voucher" required>
                                    <button type="submit" name="apply_voucher" class="btn btn-primary">Áp dụng</button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="col-md-6 text-end">
                    <div class="card shadow-sm p-3">
                        <h5 class="mb-3">Tóm tắt đơn hàng</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span><?= formatPrice($subtotal) ?></span>
                        </div>

                        <?php if ($voucherDiscount > 0): ?>
                            <div class="d-flex justify-content-between mb-2 text-success fw-bold">
                                <span>Giảm giá Voucher:</span>
                                <span>- <?= formatPrice($voucherDiscount) ?></span>
                            </div>
                        <?php endif; ?>

                        <hr>
                        <div class="h4 mt-3 d-flex justify-content-between">
                            <span>Tổng cộng:</span>
                            <span class="text-danger"><?= formatPrice($totalAmount) ?></span>
                        </div>

                        <a href="checkout.php" class="btn btn-danger mt-3">Tiến hành Thanh toán</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>