<?php
include __DIR__ . '/../header.php';

$subtotal = 0; // Tổng tiền hàng (sau khi đã trừ khuyến mãi sản phẩm)
$voucher_discount = $_SESSION['voucher']['discount_value'] ?? 0; // Số tiền được giảm từ voucher
?>

<div class="container">
    <h1 class="my-4">Giỏ hàng của bạn</h1>

    <?php if (isset($_SESSION['voucher_success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['voucher_success']; unset($_SESSION['voucher_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['voucher_error'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['voucher_error']; unset($_SESSION['voucher_error']); ?></div>
    <?php endif; ?>

    <?php if (!empty($cartItems)): ?>
        <div class="row">
            <div class="col-lg-8">
                <table class="table table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th colspan="2">Sản phẩm</th>
                            <th>Đơn giá</th>
                            <th style="width: 15%;">Số lượng</th>
                            <th>Thành tiền</th>
                            <th>Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartItems as $item): 
                            // ---- LOGIC TÍNH GIÁ MỚI ----
                            // Ưu tiên lấy giá đã giảm, nếu không có khuyến mãi thì lấy giá gốc
                            $price_to_use = $item['discounted_price'] ?? $item['price'];
                            $item_total = $price_to_use * $item['quantity'];
                            $subtotal += $item_total;
                        ?>
                        <tr>
                            <td style="width: 100px;"><img src="<?php echo htmlspecialchars($item['image_url']); ?>" class="img-fluid"></td>
                            <td>
                                <?php echo htmlspecialchars($item['name']); ?>
                                <?php if (isset($item['discount_percent']) && $item['discount_percent'] > 0): ?>
                                    <br><small class="text-muted text-decoration-line-through"><?php echo number_format($item['price'], 0, ',', '.'); ?> VNĐ</small>
                                    <span class="badge bg-danger">-<?php echo $item['discount_percent']; ?>%</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo number_format($price_to_use, 0, ',', '.'); ?> VNĐ</td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>public/update_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <div class="input-group">
                                        <input type="number" name="quantity" class="form-control" value="<?php echo $item['quantity']; ?>" min="0">
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">Cập nhật</button>
                                    </div>
                                </form>
                            </td>
                            <td><?php echo number_format($item_total, 0, ',', '.'); ?> VNĐ</td>
                            <td>
                                <form action="<?php echo BASE_URL; ?>public/remove_from_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">&times;</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Mã giảm giá</h5>
                        <form action="<?php echo BASE_URL; ?>public/apply_voucher.php" method="POST">
                            <div class="input-group">
                                <input type="text" class="form-control" name="voucher_code" placeholder="Nhập mã voucher">
                                <button type="submit" class="btn btn-primary">Áp dụng</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Tổng cộng</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Tạm tính</span>
                                <span><?php echo number_format($subtotal, 0, ',', '.'); ?> VNĐ</span>
                            </li>
                            <?php if ($voucher_discount > 0): ?>
                            <li class="list-group-item d-flex justify-content-between text-success">
                                <span>Giảm giá (<?php echo htmlspecialchars($_SESSION['voucher']['code']); ?>)
                                    <a href="<?php echo BASE_URL; ?>public/remove_voucher.php" class="text-danger ms-2">[Xóa]</a>
                                </span>
                                <span>-<?php echo number_format($voucher_discount, 0, ',', '.'); ?> VNĐ</span>
                            </li>
                            <?php endif; ?>
                            <li class="list-group-item d-flex justify-content-between fw-bold fs-5">
                                <span>Thành tiền</span>
                                <span class="text-danger"><?php echo number_format($subtotal - $voucher_discount, 0, ',', '.'); ?> VNĐ</span>
                            </li>
                        </ul>
                        <div class="d-grid mt-3">
                            <a href="<?php echo BASE_URL; ?>public/checkout.php" class="btn btn-primary">Tiến hành thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <p>Giỏ hàng của bạn đang trống.</p>
            <a href="<?php echo BASE_URL; ?>public/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../footer.php'; ?>