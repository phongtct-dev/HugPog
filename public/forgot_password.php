<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

use App\Controllers\UserController;
// Thêm thư viện mailer sau này
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;

$userController = new UserController();
$message = ''; // Biến sẽ chứa thông báo

// Logic xử lý quên mật khẩu sẽ được thêm vào đây
 $message = $userController->handleForgotPassword();

// Dữ liệu cho header
$cart_qty = 0;
$categories = [];
$is_logged_in = false;
$logged_in_username = 'Khách';

// Hiển thị view
include __DIR__ . '/../view/user/forgot_password.php';