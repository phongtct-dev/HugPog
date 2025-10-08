<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/HugPog/public/css/bootstrap.css">
    <link rel="stylesheet" href="/HugPog/public/css/style.css">
</head>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <div
            class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
            <div class="d-flex align-items-center justify-content-center w-100">
                <div class="row justify-content-center w-100">
                    <div class="col-md-8 col-lg-6 col-xxl-3">
                        <div class="card mb-0">
                            <div class="card-body">
                                <a href="index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                                    <img src="/HugPog/public/asset/image/logo.png" alt="logo" class="img-fluid custom-logo-size" style="height: 80px !important; width: auto;">
                                </a>
                                <p class="text-center">Đăng ký tài khoản mới</p>

                                <?php if (!empty($error_message)) { ?>
                                    <div class="alert alert-danger text-center"><?= $error_message ?></div>
                                <?php } ?>
                                <?php if (!empty($success_message)) { ?>
                                    <div class="alert alert-success text-center"><?= $success_message ?></div>
                                <?php } ?>

                                <form method="post" action="">
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required
                                            value="<?= htmlspecialchars($old_data['full_name']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Tên đăng nhập</label>
                                        <input type="text" class="form-control" id="username" name="username" required
                                            value="<?= htmlspecialchars($old_data['username']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Địa chỉ Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required
                                            value="<?= htmlspecialchars($old_data['email']) ?>">
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mật khẩu</label>
                                        <input type="password" class="form-control" id="password" name="password" required>
                                        <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự.</div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng ký</button>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <p class="fs-6 mb-0 mx-3">Bạn đã có tài khoản?</p>
                                        <a class="text-primary fw-bold" href="login.php">Đăng nhập</a>
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

    <script src="/HugPog/public/js/main.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>