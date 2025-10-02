<?php
// File: project/models/ReviewModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class ReviewModel {
    /**
     * Lấy tất cả các đánh giá của một sản phẩm.
     * @param int $productId
     * @return array
     */
    public function getReviewsByProductId($productId) {
        $conn = db_connect();
        // Nối với bảng users để lấy tên người đánh giá
        $sql = "SELECT pr.*, u.username 
                FROM product_reviews pr
                JOIN users u ON pr.user_id = u.id
                WHERE pr.product_id = ? AND pr.status = 'visible'
                ORDER BY pr.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $reviews;
    }

    /**
     * Tạo một đánh giá mới.
     * @param int $userId
     * @param int $productId
     * @param int $rating
     * @param string $comment
     * @return bool
     */
    public function createReview($userId, $productId, $rating, $comment) {
        $conn = db_connect();
        $sql = "INSERT INTO product_reviews (user_id, product_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis", $userId, $productId, $rating, $comment);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    //

    /**
     * Lấy TẤT CẢ các bài đánh giá cho trang admin.
     * @return array
     */
    public function getAllReviews() {
        $conn = db_connect();
        // Nối các bảng để lấy tên người dùng và tên sản phẩm
        $sql = "SELECT pr.id, pr.rating, pr.comment, pr.status, pr.created_at, u.username, p.name as product_name
                FROM product_reviews pr
                JOIN users u ON pr.user_id = u.id
                JOIN products p ON pr.product_id = p.id
                ORDER BY pr.created_at DESC";
        $result = $conn->query($sql);
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $reviews;
    }

    /**
     * Cập nhật trạng thái của một bài đánh giá (visible/hidden).
     * @param int $reviewId
     * @param string $newStatus
     * @return bool
     */
    public function updateReviewStatus($reviewId, $newStatus) {
        $conn = db_connect();
        $sql = "UPDATE product_reviews SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $reviewId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }
}
?>