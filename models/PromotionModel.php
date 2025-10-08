<?php
// File: project/models/PromotionModel.php
require_once __DIR__ . '/../includes/db_connect.php';

class PromotionModel
{
    public function getAllPromotions()
    {
        $conn = db_connect();
        $sql = "SELECT pd.*, p.name as product_name 
                FROM product_discounts pd
                JOIN products p ON pd.product_id = p.id
                ORDER BY pd.end_date DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function createPromotion($data)
    {
        $conn = db_connect();
        $sql = "INSERT INTO product_discounts (product_id, discount_percent, start_date, end_date, created_by_staff_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idssi", $data['product_id'], $data['discount_percent'], $data['start_date'], $data['end_date'], $data['staff_id']);
        return $stmt->execute();
    }

    public function deletePromotion($id)
    {
        $conn = db_connect();
        $sql = "DELETE FROM product_discounts WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function getPromotionByProductId($productId)
    {
        $conn = db_connect();
        $sql = "
        SELECT 
            pd.id,
            pd.product_id,
            pd.discount_percent,
            pd.start_date,
            pd.end_date,
            p.name AS product_name
        FROM product_discounts pd
        JOIN products p ON pd.product_id = p.id
        WHERE pd.product_id = ?
          AND NOW() BETWEEN pd.start_date AND pd.end_date
        ORDER BY pd.start_date DESC
        LIMIT 1
    ";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            error_log("SQL prepare failed: " . $conn->error);
            return null;
        }

        $stmt->bind_param("i", $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // trả về thông tin khuyến mãi mới nhất
        }

        return null; // không có khuyến mãi
    }
    
}
