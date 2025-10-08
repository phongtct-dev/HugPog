<?php
// Nhúng Controller
// **CẦN ĐIỀU CHỈNH ĐƯỜNG DẪN DỰA TRÊN CẤU TRÚC THƯ MỤC CỦA BẠN**
require_once __DIR__ . '../../../includes/controllers/UserController.php';

// Khởi tạo Controller
$userController = new UserController();

// Gọi hàm xử lý đăng nhập.
// Nếu đăng nhập thành công, Controller sẽ tự chuyển hướng.
// Nếu thất bại, nó sẽ trả về mảng lỗi.
$errors = $userController->handleLogin();

// Nếu có thông báo lỗi/thành công từ các trang khác (ví dụ: đăng ký thành công)
$success = $_SESSION['success_message'] ?? '';
unset($_SESSION['success_message']);

// Biến $error sẽ được gán là lỗi đầu tiên trong mảng nếu có
$error = empty($errors) ? '' : $errors[0];
?>
<?php
include_once __DIR__ . '../../../includes/config.php';
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
        data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="index.php"" class=" text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="/HugPog/public/asset/image/logo.png" alt="logo" class="img-fluid custom-logo-size" style="height: 80px !important; width: auto;">
                                </a>
                                <p class="text-center s">Đăng Nhập</p>

                                <?php if (!empty($success)) { // Hiển thị thông báo thành công 
                                ?>
                                    <div class="alert alert-success text-center"><?= $success ?></div>
                                <?php } ?>

                                <?php if (!empty($error)) { // Hiển thị lỗi đăng nhập 
                                ?>
                                    <div class="alert alert-danger text-center"><?= $error ?></div>
                                <?php } ?>

                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control" id="username" name="username" required
                                            value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                    </div>
                                    <div class="mb-4">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input primary" type="checkbox" id="remember" name="remember">
                                            <label class="form-check-label text-dark" for="remember">
                                                Ghi nhớ thiết bị này
                                            </label>
                                        </div>
                                        <a class="text-primary fw-bold ms-1" href="forgot_password.php">Quên mật khẩu?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng nhập</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-8 mb-0 ">Tạo tài khoản mới</p>
                                        <a class="text-primary fw-bold ms-3" href="register.php">Tạo tài khoản</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
    <script src="/HugPog/public/js/main.js"></script>

</body>

</html>