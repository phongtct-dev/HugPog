<?php
// File: project/View/user/register.php

// Nhúng header vào
include __DIR__ . '/../header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Đăng ký tài khoản</h3>
            </div>
            <div class="card-body">
                <?php
                // Vòng lặp để hiển thị tất cả các lỗi từ Controller
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">';
                    foreach ($errors as $error) {
                        echo "<p class='mb-0'>" . htmlspecialchars($error) . "</p>";
                    }
                    echo '</div>';
                }
                ?>
                
                <form action="register.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" id="full_name" name="full_name">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                
                <p class="text-center mt-3">
                    Đã có tài khoản? <a href="login.php">Đăng nhập ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
// Nhúng footer vào
include __DIR__ . '/../footer.php';
?>