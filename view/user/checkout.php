<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="section">
        <div class="container">
            <div class="row">
                <form id="checkout-form" action="<?php echo BASE_URL; ?>public/place_order.php" method="POST">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="billing-details">
                                <div class="section-title">
                                    <h3 class="title">Địa chỉ thanh toán</h3>
                                </div>
                                <div class="form-group mb-3">
                                    <input class="input" type="text" name="full_name" placeholder="Họ và tên"
                                        value="<?= htmlspecialchars($currentUser['full_name'] ?? '') ?>" required />
                                </div>
                                <div class="form-group mb-3">
                                    <input class="input" type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" readonly />
                                </div>
                                <div class="form-group mb-3">
                                    <input class="input" type="text" name="address" placeholder="Địa chỉ giao hàng" value="<?php echo htmlspecialchars($currentUser['address'] ?? ''); ?>" required />
                                </div>
                                <div class="form-group mb-3">
                                    <input class="input" type="tel" name="phone" placeholder="Số điện thoại" value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>" required />
                                </div>
                                <div class="order-notes mt-4">
                                    <textarea class="input" placeholder="Ghi chú đơn hàng (Tùy chọn)" name="order_notes"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="order-details">
                                <div class="section-title text-center">
                                    <h3 class="title">ĐƠN HÀNG CỦA BẠN</h3>
                                </div>
                                <div class="order-summary">
                                    <div class="order-col">
                                        <div><strong>SẢN PHẨM</strong></div>
                                        <div><strong>TỔNG CỘNG</strong></div>
                                    </div>
                                    <?php foreach ($cartItems as $item): ?>
                                        <div class="order-products">
                                            <div class="order-col">
                                                <div><?php echo htmlspecialchars($item['quantity']); ?>x <?php echo htmlspecialchars($item['name']); ?></div>
                                                <div><?php echo format_currency($item['total']); ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <hr>
                                    <div class="order-col">
                                        <div>Tạm tính:</div>
                                        <div><?php echo formatPrice($subtotal); ?></div>
                                    </div>
                                    <?php if ($voucherDiscount > 0): ?>
                                        <div class="order-col">
                                            <div>Giảm giá (Voucher)</div>
                                            <div><strong>-<?php echo formatPrice($voucherDiscount); ?></strong></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="order-col">
                                    <div><strong>TỔNG CỘNG</strong></div>
                                    <div><strong class="order-total"><?php echo formatPrice($totalAmount); ?></strong></div>
                                </div>
                                <div class="payment-method mt-4">
                                    <div class="input-radio">
                                        <input type="radio" name="payment" id="payment-1" value="cod" checked>
                                        <label for="payment-1">
                                            <span></span>
                                            Thanh toán khi nhận hàng (COD)
                                        </label>
                                    </div>
                                </div>
                                <div class="input-checkbox mt-3">
                                    <input type="checkbox" id="terms" required>
                                    <label for="terms">
                                        <span></span>
                                        Tôi đã đọc và chấp nhận <a href="#">Các Điều khoản & Điều kiện</a>
                                    </label>
                                </div>
                                <button type="submit" class="primary-btn order-submit w-100 mt-3">ĐẶT HÀNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layout/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
</body>

</html>