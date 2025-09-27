<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Flexy Free Bootstrap Admin Template by WrapPixel</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/logos/favicon.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
  data-sidebar-position="fixed" data-header-position="fixed">
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
              <p class="text-center">Đăng ký</p>

              <!-- Form PHP -->
              <form method="post" action="signup.php">
                <div class="mb-3">
                  <label for="exampleInputtext1" class="form-label">Họ và tên</label>
                  <input type="text" class="form-control" id="exampleInputtext1" name="name" required>
                </div>
                <div class="mb-3">
                  <label for="exampleInputEmail1" class="form-label">Địa chỉ Email</label>
                  <input type="email" class="form-control" id="exampleInputEmail1" name="email" required>
                </div>
                <div class="mb-4">
                  <label for="exampleInputPassword1" class="form-label">Mật khẩu</label>
                  <input type="password" class="form-control" id="exampleInputPassword1" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Đăng ký</button>
                <div class="d-flex align-items-center justify-content-center">
                  <p class="fs-6 mb-0 mx-3">Bạn đã có tài khoản?</p>
                  <a class="text-primary fw-bold" href="./login.php">Đăng nhập</a>
                </div>
              </form>
              <!-- End Form -->
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
  <!-- solar icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
</body>

</html>