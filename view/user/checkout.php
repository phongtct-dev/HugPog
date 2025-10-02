<?php
// File: project/View/user/checkout.php

include __DIR__ . '/../header.php';
?>

<div class="container">
    <h1 class="my-4">Thanh toán</h1>
    <div class="row">
        <div class="col-md-8">
            <h4>Thông tin giao hàng</h4>
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>public/place_order.php" method="POST">
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Họ và tên người nhận</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Xác nhận và Đặt hàng</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <h4>Đơn hàng của bạn</h4>
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <?php 
                        $subtotal = 0;
                        $discount = $_SESSION['voucher']['discount_value'] ?? 0;
                        ?>
                        <?php foreach ($cartItems as $item): 
                            $item_total = $item['price'] * $item['quantity'];
                            $subtotal += $item_total;
                        ?>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                                <span><?php echo number_format($item_total, 0, ',', '.'); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tạm tính</span>
                            <span><?php echo number_format($subtotal, 0, ',', '.'); ?></span>
                        </li>
                        <?php if ($discount > 0): ?>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span>Giảm giá</span>
                            <span>-<?php echo number_format($discount, 0, ',', '.'); ?></span>
                        </li>
                        <?php endif; ?>
                         <li class="list-group-item d-flex justify-content-between fw-bold fs-5">
                            <span>Thành tiền</span>
                            <span class="text-danger"><?php echo number_format($subtotal - $discount, 0, ',', '.'); ?> VNĐ</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include __DIR__ . '/../footer.php';
?>