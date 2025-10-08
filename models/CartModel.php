<?php
// File: project/models/CartModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class CartModel
{

    /**
     * Thêm sản phẩm vào giỏ hàng hoặc cập nhật số lượng nếu đã tồn tại.
     * Sử dụng cú pháp "ON DUPLICATE KEY UPDATE" rất hiệu quả.
     * @param int $userId ID của người dùng.
     * @param int $productId ID của sản phẩm.
     * @param int $quantity Số lượng cần thêm.
     * @return bool True nếu thành công, false nếu thất bại.
     */
    public function upsertItem($userId, $productId, $quantity)
    {
        $conn = db_connect();
        $sql = "INSERT INTO carts (user_id, product_id, quantity) VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $userId, $productId, $quantity);

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            return true;
        }
        $stmt->close();
        $conn->close();
        return false;
    }

    /**
     * Lấy tổng số lượng sản phẩm trong giỏ hàng của người dùng.
     * @param int $userId ID người dùng.
     * @return int Tổng số lượng.
     */
    public function getCartItemCount($userId)
    {
        $conn = db_connect();
        $sql = "SELECT SUM(quantity) as total_items FROM carts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        // Nếu không có sản phẩm nào, $row['total_items'] sẽ là NULL, nên ta trả về 0
        return $row['total_items'] ?? 0;
    }

    // Các hàm để lấy chi tiết giỏ hàng, cập nhật, xóa 

    /**
     * Lấy tất cả sản phẩm trong giỏ hàng của user cùng với thông tin chi tiết.
     * @param int $userId ID người dùng.
     * @return array Mảng chứa các sản phẩm trong giỏ hàng.
     */
    public function getCartItemsByUserId($userId)
    {
        $conn = db_connect();
        // THÊM LEFT JOIN VÀO BẢNG KHUYẾN MÃI
        $sql = "SELECT 
                p.id, p.name, p.price, p.image_url, p.stock, 
                c.quantity,
                pd.discount_percent,
                (p.price * (1 - pd.discount_percent / 100)) as discounted_price
            FROM carts c
            JOIN products p ON c.product_id = p.id
            LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
            WHERE c.user_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();
        return $items;
    }

    /**
     * Cập nhật số lượng của một sản phẩm trong giỏ hàng.
     * @param int $userId ID người dùng.
     * @param int $productId ID sản phẩm.
     * @param int $quantity Số lượng mới.
     * @return bool True nếu thành công.
     */
    public function updateItemQuantity($userId, $productId, $quantity)
    {
        $conn = db_connect();
        $sql = "UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $quantity, $userId, $productId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Xóa một sản phẩm khỏi giỏ hàng.
     * @param int $userId ID người dùng.
     * @param int $productId ID sản phẩm.
     * @return bool True nếu thành công.
     */
    public function removeItem($userId, $productId)
    {
        $conn = db_connect();
        $sql = "DELETE FROM carts WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Lấy tổng số lượng sản phẩm trong giỏ hàng của người dùng.
     * @param int $userId ID người dùng.
     * @return int Tổng số lượng.
     */
    public function getTotalQuantity($userId)
    {
        $conn = db_connect();
        $sql = "SELECT SUM(quantity) as total_qty FROM carts WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return (int)($row['total_qty'] ?? 0);
    }
}
