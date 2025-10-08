<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
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

    <div class="section">
        <div class="container">
            <div class="row">
                <form id="checkout-form" action="<?= BASE_URL; ?>public/place_order.php" method="POST">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="billing-details">
                                <div class="section-title">
                                    <h3 class="title">Địa chỉ thanh toán</h3>
                                </div>

                                <div class="form-group mb-3">
                                    <input class="input" type="text" name="full_name" placeholder="Họ và tên"
                                        value="<?= htmlspecialchars($currentUser['fullname'] ?? '') ?>" required />
                                </div>

                                <div class="form-group mb-3">
                                    <input class="input" type="email" name="email" placeholder="Email"
                                        value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" readonly />
                                </div>

                                <div class="form-group mb-3">
                                    <input class="input" type="text" name="address" placeholder="Địa chỉ giao hàng"
                                        value="<?= htmlspecialchars($currentUser['address'] ?? '') ?>" required />
                                </div>

                                <div class="form-group mb-3">
                                    <input class="input" type="tel" name="phone" placeholder="Số điện thoại"
                                        value="<?= htmlspecialchars($currentUser['phone'] ?? '') ?>" required />
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
                                        <?php $itemTotal = ($item['discounted_price'] ?? $item['price']) * $item['quantity']; ?>
                                        <div class="order-products">
                                            <div class="order-col">
                                                <div><?= htmlspecialchars($item['quantity']) ?>x <?= htmlspecialchars($item['name']) ?></div>
                                                <div><?= format_currency($itemTotal) ?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <span>------------------------------------------------------------</span>

                                    <div class="order-col">
                                        <div>Tạm tính:</div>
                                        <div><?= formatPrice($subtotal) ?></div>
                                    </div>
                                    <div class="order-col">
                                        <div>Giảm giá (Voucher)</div>
                                        <div><strong>-<?= formatPrice($voucherDiscount) ?></strong></div>
                                    </div>
                                </div>

                                <div class="order-col">
                                    <div><strong>TỔNG CỘNG</strong></div>
                                    <div><strong class="order-total"><?= formatPrice($totalAmount) ?></strong></div>
                                </div>

                                <div class="payment-method">
                                    <div class="input-radio">
                                        <input type="radio" name="payment" id="payment-1" value="cod" checked>
                                        <label for="payment-1">
                                            <span></span>
                                            Thanh toán khi nhận hàng (COD)
                                        </label>
                                    </div>
                                </div>

                                <div class="input-checkbox">
                                    <input type="checkbox" id="terms" required>
                                    <label for="terms">
                                        <span></span>
                                        Tôi đã đọc và chấp nhận <a href="#">Các Điều khoản & Điều kiện</a>
                                    </label>
                                </div>

                                <button type="submit" class="primary-btn order-submit">ĐẶT HÀNG</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>
</body>

</html>