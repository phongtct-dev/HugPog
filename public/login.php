<?php
// Bắt đầu phiên làm việc
session_start();

$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Dữ liệu tài khoản giả định (bạn nên thay bằng dữ liệu từ cơ sở dữ liệu)
  $accounts = [
    'admin' => ['password' => '123456', 'role' => 'admin'],
    'user123' => ['password' => '654321', 'role' => 'user']
  ];

  // Kiểm tra thông tin đăng nhập
  if (isset($accounts[$username]) && $accounts[$username]['password'] === $password) {
    // Đăng nhập thành công
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $accounts[$username]['role'];

    // Chuyển hướng dựa trên vai trò
    if ($_SESSION['role'] === 'admin') {
      header("Location: admin/admin_dashboard.php");
    } else {
      header("Location: index.php");
    }
    exit();
  } else {
    $error = "Sai tên đăng nhập hoặc mật khẩu!";
  }
}

// Nếu đã đăng nhập, chuyển hướng đến trang dashboard phù hợp
if (isset($_SESSION['username'])) {
  if ($_SESSION['role'] === 'admin') {
    header("Location: admin/admin_dashboard.php");
  } else {
    header("Location: index.php");
  }
  exit();
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - Flexy Admin</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/logos/favicon.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
  data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <div
    class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-3">
          <div class="card mb-0">
            <div class="card-body">
              <a href="./index.php" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="../public/asset/image/logo.png" alt="logo" class="img-fluid custom-logo-size" style="height: 80px !important; width: auto;"> 
              </a>
              <p class="text-center s">Đăng Nhập</p>

              <?php if (!empty($error)) { ?>
              <div class="alert alert-danger text-center"><?= $error ?></div>
              <?php } ?>

              <form method="post" action="">
                <div class="mb-3">
                  <label for="username" class="form-label">Tên đăng nhập</label>
                  <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Mật khẩu</label>
                  <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input class="form-check-input primary" type="checkbox" id="remember">
                    <label class="form-check-label text-dark" for="remember">
                      Ghi nhớ thiết bị này
                    </label>
                  </div>
                  <a class="text-primary fw-bold ms-1" href="forgot_password.php">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng nhập</button>
                <div class="d-flex align-items-center justify-content-center">
                  <p class="fs-8 mb-0 ">Tạo tài khoản mới</p>
                  <a class="text-primary fw-bold ms-3" href="./register.php">Tạo tài khoản</a>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>