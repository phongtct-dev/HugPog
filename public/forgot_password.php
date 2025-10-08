<?php
// Bắt đầu phiên làm việc
session_start();

// Nhúng lớp UserModel
// **CẦN ĐIỀU CHỈNH ĐƯỜNG DẪN nếu cấu trúc thư mục của bạn khác**
// Giả định file UserModel.php nằm ở thư mục cha của public/
require_once '../models/UserModel.php';

$userModel = new UserModel();
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');

  // 1. Kiểm tra định dạng Email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error_message = 'Địa chỉ Email không hợp lệ.';
  } else {
    // 2. Kiểm tra Email tồn tại trong hệ thống
    $user = $userModel->getUserByEmail($email);

    if ($user) {
      // --- XỬ LÝ ĐẶT LẠI MẬT KHẨU ---

      // 3. TẠO MẬT KHẨU MỚI TẠM THỜI (8 ký tự ngẫu nhiên)
      $temp_password = substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ23456789'), 0, 8);

      // 4. HASH MẬT KHẨU MỚI (Luôn phải hash trước khi lưu)
      $new_hashed_password = password_hash($temp_password, PASSWORD_DEFAULT);

      // 5. CẬP NHẬT MẬT KHẨU VÀO DATABASE
      if ($userModel->resetPasswordByEmail($email, $new_hashed_password)) {

        // 6. MÔ PHỎNG GỬI EMAIL
        /*
                CẢNH BÁO AN TOÀN QUAN TRỌNG:
                Trong môi trường thực tế, bạn cần sử dụng PHPMailer để gửi email 
                qua SMTP. 
                LIÊN KẾT ĐẶT LẠI MẬT KHẨU (token) là phương pháp an toàn hơn 
                việc gửi mật khẩu tạm thời.
                */

        // --- KÍCH HOẠT HÀM GỬI EMAIL THỰC TẾ (Nếu đã cấu hình SMTP/PHPMailer) ---
        // mail($email, "Mật khẩu tạm thời", "Mật khẩu mới: " . $temp_password, "From: noreply@yourdomain.com");
        // ------------------------------------------------------------------------

        // Thay thế thông báo thành công (chỉ dùng khi TEST):
        $success_message = 'Mật khẩu mới đã được cập nhật trong database! Mật khẩu tạm thời (CHỈ DÀNH CHO BẠN TEST) là: <strong>' . htmlspecialchars($temp_password) . '</strong>. Vui lòng thử đăng nhập.';
      } else {
        $error_message = 'Đã xảy ra lỗi khi cập nhật mật khẩu. Vui lòng thử lại.';
      }
    } else {
      $error_message = 'Địa chỉ email này không tồn tại trong hệ thống.';
    }
  }
}

include '../view/user/forgot_password.php';

?>