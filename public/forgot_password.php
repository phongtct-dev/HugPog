<?php
// Bắt đầu phiên làm việc để lưu thông báo (dù không dùng session ở đây, nên giữ nếu dùng Bootstrap)
session_start();

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    
    // --- BƯỚC 1: XÁC THỰC EMAIL CƠ BẢN ---
    if (empty($email)) {
        $error_message = "Vui lòng nhập địa chỉ Email của bạn.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Địa chỉ Email không hợp lệ.";
    } else {
        // --- BƯỚC 2: TÌM EMAIL TRONG CSDL VÀ XỬ LÝ (GIẢ LẬP) ---
        
        // **TRONG THỰC TẾ:**
        // 1. Kết nối CSDL và tìm kiếm email.
        // 2. Nếu tìm thấy:
        //    a. TẠO MỘT MẬT KHẨU MỚI NGẪU NHIÊN.
        //    b. BĂM (HASH) MẬT KHẨU MỚI và CẬP NHẬT vào CSDL.
        //    c. GỬI MẬT KHẨU MỚI KHÔNG BĂM NÀY QUA EMAIL cho người dùng.
        // 3. Nếu không tìm thấy, thông báo thành công chung chung để bảo mật.

        // **GIẢ LẬP XỬ LÝ** (Để bảo mật, chúng ta luôn thông báo thành công dù email tồn tại hay không)
        $success_message = "Yêu cầu của bạn đã được gửi. Nếu địa chỉ email của bạn tồn tại trong hệ thống, **mật khẩu mới** đã được gửi đến hộp thư của bạn.";
        
        // **LƯU Ý QUAN TRỌNG VỀ BẢO MẬT:**
        // Việc gửi mật khẩu mới qua email là không an toàn. Tốt nhất là sử dụng 
        // liên kết đặt lại mật khẩu với token hết hạn như đã đề xuất trước.
        
        // Đây là nơi bạn sẽ gọi hàm gửi email (sử dụng thư viện như PHPMailer)
        // Ví dụ: send_new_password($email, $new_unhashed_password);
    }
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Quên Mật Khẩu</title>
  <link rel="shortcut icon" type="image/png" href="./assets/images/logos/favicon.png" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <h2 class="text-center">Quên Mật Khẩu</h2>
                <p class="text-center mb-5">Nhập email của bạn để chúng tôi gửi lại mật khẩu mới.</p>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST">
                  <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                  </div>
                  
                  <button type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2">Gửi mật khẩu mới</button>
                  
                  <div class="d-flex align-items-center justify-content-center">
                    <a class="text-primary fw-bold" href="./login.php">Quay lại Đăng nhập</a>
                  </div>
                </form>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>