<?php
// FILE: public/checkout.php (PHIÊN BẢN ĐÃ SỬA)

// 1. Nạp Autoloader và Config
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/config.php';

// 2. Khai báo các lớp sẽ sử dụng
use App\Controllers\CartController;
use App\Controllers\CategoryController;
use App\Models\UserModel; // Rất quan trọng!

// 3. Kiểm tra điều kiện đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'public/login.php');
    exit();
}

// 4. Khởi tạo Controller và Model
$cartController = new CartController();
$categoryController = new CategoryController();
$userModel = new UserModel();

// 5. Chuẩn bị dữ liệu để hiển thị

// Dữ liệu cho Header
$cart_qty = $cartController->getCartQuantity();
$categories = $categoryController->listCategories();
$is_logged_in = true;
$logged_in_username = $_SESSION['username'] ?? 'Khách';

// Dữ liệu cho nội dung chính của trang
$cartData = $cartController->getCartItems();
$cartItems = $cartData['items'];
$subtotal = $cartData['subtotal'];

// Nếu giỏ hàng rỗng, chuyển về trang giỏ hàng
if (empty($cartItems)) {
    header('Location: ' . BASE_URL . 'public/cart.php');
    exit();
}

// Ưu tiên lấy thông tin từ session đã lưu để tự động điền form
$currentUser = $_SESSION['user_profile'] ?? [];
// Lấy thêm email từ CSDL (vì chúng ta không lưu email trong session profile)
$userFullInfo = $userModel->getUserById($_SESSION['user_id']);
$currentUser['email'] = $userFullInfo['email'] ?? '';


// Tính toán tổng tiền cuối cùng
$totalsData = $cartController->calculateCartTotals($subtotal);
$voucherDiscount = $totalsData['voucherDiscount'];
$totalAmount = $totalsData['totalAmount'];

// Các hàm helper
function format_currency($price) {
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
function formatPrice($price) {
    return format_currency($price);
}

// 6. Hiển thị View
include __DIR__ . '/../view/user/checkout.php';