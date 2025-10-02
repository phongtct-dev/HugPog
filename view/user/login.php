<?php
// File: project/View/user/login.php

// Nhúng header
include __DIR__ . '/../header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Đăng nhập</h3>
            </div>
            <div class="card-body">
                <?php
                // Hiển thị thông báo thành công từ trang đăng ký
                if (isset($_SESSION['success_message'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                    // Xóa thông báo sau khi hiển thị để không hiện lại
                    unset($_SESSION['success_message']);
                }
                
                // Hiển thị các lỗi đăng nhập
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">';
                    foreach ($errors as $error) {
                        echo "<p class='mb-0'>" . htmlspecialchars($error) . "</p>";
                    }
                    echo '</div>';
                }

                // cần đăng nhập
                if (isset($_SESSION['error_message'])) {
                    echo '<div class="alert alert-warning">' . $_SESSION['error_message'] . '</div>';
                    unset($_SESSION['error_message']); // Xóa thông báo sau khi hiển thị
                }
                ?>

                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>

                <p class="text-center mt-3">
                    Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php
// Nhúng footer
include __DIR__ . '/../footer.php';
?>