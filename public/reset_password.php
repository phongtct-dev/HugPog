<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\UserController;
use App\Models\UserModel;

$token = $_GET['token'] ?? '';
if (empty($token)) {
    die('Token không hợp lệ.');
}

$userModel = new UserModel();
$resetRequest = $userModel->findPasswordResetByToken($token);

// Nếu token không tồn tại hoặc đã hết hạn, hiển thị thông báo
if (!$resetRequest) {
    $tokenIsValid = false;
    $message = ['type' => 'danger', 'text' => 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng thực hiện lại yêu cầu "Quên mật khẩu".'];
} else {
    $tokenIsValid = true;
}

$userController = new UserController();
// Xử lý khi người dùng gửi form mật khẩu mới
if ($tokenIsValid) {
    $message = $userController->handleResetPassword($token);
}

// Dữ liệu cho header
$cart_qty = 0;
$categories = [];
$is_logged_in = false;
$logged_in_username = 'Khách';

include __DIR__ . '/../view/user/reset_password.php';