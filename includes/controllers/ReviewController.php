<?php
// File: project/includes/controllers/ReviewController.php

require_once __DIR__ . '/../../models/ReviewModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../config.php';

class ReviewController {
    /**
     * Xử lý việc gửi đánh giá.
     */
    public function handleSubmitReview() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $productId = intval($_POST['product_id']);
            $rating = intval($_POST['rating']);
            $comment = trim($_POST['comment']);

            // 1. Kiểm tra xem người dùng đã mua sản phẩm này chưa
            $orderModel = new OrderModel();
            if (!$orderModel->hasUserPurchasedProduct($userId, $productId)) {
                $_SESSION['error_message'] = "Bạn chỉ có thể đánh giá sản phẩm đã mua.";
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit();
            }
            
            // 2. Validate dữ liệu
            if ($rating >= 1 && $rating <= 5 && !empty($comment)) {
                $reviewModel = new ReviewModel();
                $reviewModel->createReview($userId, $productId, $rating, $comment);
                $_SESSION['success_message'] = "Cảm ơn bạn đã gửi đánh giá!";
            } else {
                 $_SESSION['error_message'] = "Vui lòng chấm điểm và viết bình luận.";
            }
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    //

    /**
     * Lấy danh sách đánh giá cho trang admin.
     * @return array
     */
    public function listReviewsForAdmin() {
        $reviewModel = new ReviewModel();
        return $reviewModel->getAllReviews();
    }

    /**
     * Xử lý việc cập nhật trạng thái đánh giá từ admin.
     */
    public function handleUpdateReviewStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'], $_POST['current_status'])) {
            $reviewId = intval($_POST['review_id']);
            $currentStatus = $_POST['current_status'];
            
            // Đảo ngược trạng thái: nếu đang visible thì thành hidden, và ngược lại
            $newStatus = ($currentStatus === 'visible') ? 'hidden' : 'visible';
            
            $reviewModel = new ReviewModel();
            $reviewModel->updateReviewStatus($reviewId, $newStatus);
        }
        // Chuyển hướng về lại trang quản lý đánh giá
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>