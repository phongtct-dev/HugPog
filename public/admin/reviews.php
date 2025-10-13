<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../includes/helpers/admin_auth.php';

require_admin_login();

use App\Controllers\ReviewController;

$reviewController = new ReviewController();

// Xử lý POST request nếu có
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reviewController->handleAdminReviewAction();
}

// Lấy dữ liệu cho view
$reviews = $reviewController->listReviewsForAdmin();
$message = '';
if (isset($_SESSION['message'])) {
    $type = $_SESSION['message']['type'];
    $content = $_SESSION['message']['content'];
    $message = '<div class="alert alert-' . ($type === 'success' ? 'success' : 'danger') . '">' . htmlspecialchars($content) . '</div>';
    unset($_SESSION['message']);
}

// Các hàm helper cho view
function displayRatingStars($rating) {
    $html = '<div class="text-warning">';
    for ($i = 1; $i <= 5; $i++) {
        $html .= $i <= (int)$rating ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
    }
    return $html . '</div>';
}

function displayStatusBadge($status) {
    switch ($status) {
        case 'visible': return '<span class="badge bg-success">Đang hiển thị</span>';
        case 'hidden': return '<span class="badge bg-danger">Đã ẩn</span>';
        default: return '<span class="badge bg-secondary">Chờ duyệt</span>';
    }
}

// Hiển thị view
include __DIR__ . '/../../view/admin/review_management.php';