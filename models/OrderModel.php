<?php
// File: project/models/OrderModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class OrderModel {
    /**
     * Tạo một đơn hàng mới và các chi tiết đơn hàng tương ứng.
     * Đây là một giao dịch (transaction) để đảm bảo toàn vẹn dữ liệu.
     * @param int $userId
     * @param string $customerName
     * @param string $shippingAddress
     * @param string $phone
     * @param array $cartItems Mảng các sản phẩm trong giỏ hàng
     * @param float $totalAmount Tổng giá trị đơn hàng
     * @return int|false Trả về ID của đơn hàng nếu thành công, ngược lại là false.
     */
    /**
     * Tạo một đơn hàng mới (Phiên bản nâng cấp có xử lý Voucher).
     * @param int $userId
     * @param string $customerName
     * @param string $shippingAddress
     * @param string $phone
     * @param array $cartItems
     * @param float $totalAmount
     * @param array|null $voucher Thông tin voucher từ session
     * @return int|false
     */
    public function createOrder($userId, $customerName, $shippingAddress, $phone, $cartItems, $totalAmount, $voucher) {
    $conn = db_connect();
    $conn->begin_transaction();

    try {
        $voucherId = $voucher['id'] ?? null;
        $voucherCode = $voucher['code'] ?? null;
        $discountAmount = $voucher['discount_value'] ?? 0.00;

        // 1. Chèn vào bảng `orders`
        $sqlOrder = "INSERT INTO orders (user_id, customer_name, shipping_address, phone, total_amount, voucher_id, voucher_code, discount_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("isssdisd", $userId, $customerName, $shippingAddress, $phone, $totalAmount, $voucherId, $voucherCode, $discountAmount);
        $stmtOrder->execute();
        $orderId = $conn->insert_id;
        $stmtOrder->close();

        // 2. Chèn vào bảng `order_items` VỚI GIÁ ĐÚNG
        $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
        $stmtItem = $conn->prepare($sqlItem);
        foreach ($cartItems as $item) {
            // Ưu tiên lấy giá đã giảm, nếu không có thì lấy giá gốc
            $price_at_purchase = $item['discounted_price'] ?? $item['price'];

            $stmtItem->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $price_at_purchase);
            $stmtItem->execute();
        }
        $stmtItem->close();

        // 3. Cập nhật số lượng voucher (nếu có)
        if ($voucherId) {
            $sqlUpdateVoucher = "UPDATE vouchers SET quantity = quantity - 1 WHERE id = ?";
            $stmtUpdateVoucher = $conn->prepare($sqlUpdateVoucher);
            $stmtUpdateVoucher->bind_param("i", $voucherId);
            $stmtUpdateVoucher->execute();
            $stmtUpdateVoucher->close();
        }

        // 4. Xóa giỏ hàng
        $sqlDeleteCart = "DELETE FROM carts WHERE user_id = ?";
        $stmtDeleteCart = $conn->prepare($sqlDeleteCart);
        $stmtDeleteCart->bind_param("i", $userId);
        $stmtDeleteCart->execute();
        $stmtDeleteCart->close();

        $conn->commit();
        $conn->close();
        return $orderId;

            } catch (Exception $e) {
                $conn->rollback();
                $conn->close();
                error_log($e->getMessage());
                return false;
            }
    }

    //

    /**
     * Lấy tất cả đơn hàng để hiển thị cho admin.
     * @return array Danh sách các đơn hàng.
     */
    public function getAllOrders() {
        $conn = db_connect();
        // Sắp xếp theo ngày tạo mới nhất
        $sql = "SELECT * FROM orders ORDER BY created_at DESC";
        $result = $conn->query($sql);
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $orders;
    }

    /**
     * Lấy thông tin chi tiết của một đơn hàng, bao gồm cả các sản phẩm trong đó.
     * @param int $orderId ID của đơn hàng.
     * @return array|null Mảng chứa thông tin chi tiết hoặc null nếu không tìm thấy.
     */
    public function getOrderDetailsById($orderId) {
        $conn = db_connect();
        $orderInfo = null;

        // 1. Lấy thông tin chính của đơn hàng
        $sqlOrder = "SELECT * FROM orders WHERE id = ?";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("i", $orderId);
        $stmtOrder->execute();
        $resultOrder = $stmtOrder->get_result();
        $orderInfo = $resultOrder->fetch_assoc();
        $stmtOrder->close();

        if ($orderInfo) {
            // 2. Lấy danh sách sản phẩm của đơn hàng đó
            $sqlItems = "SELECT oi.quantity, oi.price, p.name, p.image_url 
                         FROM order_items oi
                         JOIN products p ON oi.product_id = p.id
                         WHERE oi.order_id = ?";
            $stmtItems = $conn->prepare($sqlItems);
            $stmtItems->bind_param("i", $orderId);
            $stmtItems->execute();
            $resultItems = $stmtItems->get_result();
            // Gán danh sách sản phẩm vào mảng thông tin đơn hàng
            $orderInfo['items'] = $resultItems->fetch_all(MYSQLI_ASSOC);
            $stmtItems->close();
        }
        
        $conn->close();
        return $orderInfo;
    }

    /**
     * Cập nhật trạng thái của một đơn hàng.
     * @param int $orderId ID đơn hàng.
     * @param string $newStatus Trạng thái mới.
     * @return bool
     */
    public function updateOrderStatus($orderId, $newStatus) {
        $conn = db_connect();
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $orderId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    //


    /**
     * Lấy danh sách đơn hàng cho trang admin.
     * @return array
     */
    public function listOrdersForAdmin() {
        $orderModel = new OrderModel();
        return $orderModel->getAllOrders();
    }
    
    /**
     * Lấy chi tiết đơn hàng cho trang admin.
     * @return array|null
     */
    public function showOrderDetailForAdmin() {
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $orderId = intval($_GET['id']);
            $orderModel = new OrderModel();
            return $orderModel->getOrderDetailsById($orderId);
        }
        return null;
    }

    /**
     * Xử lý việc cập nhật trạng thái đơn hàng từ admin.
     */
    public function handleUpdateStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
            $orderId = intval($_POST['order_id']);
            $newStatus = $_POST['status'];
            
            $orderModel = new OrderModel();
            $orderModel->updateOrderStatus($orderId, $newStatus);
        }
        // Chuyển hướng về lại trang chi tiết đơn hàng
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    //

    /**
     * Lấy tất cả đơn hàng của một người dùng cụ thể.
     * @param int $userId
     * @return array
     */
    public function getOrdersByUserId($userId) {
        $conn = db_connect();
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        $conn->close();
        return $orders;
    }

    /**
     * Tìm một đơn hàng cụ thể CỦA một người dùng.
     * Rất quan trọng để đảm bảo người dùng không xem được đơn hàng của người khác.
     * @param int $orderId
     * @param int $userId
     * @return array|null
     */
    public function findUserOrderById($orderId, $userId) {
        // Hàm này gần giống getOrderDetailsById nhưng có thêm điều kiện user_id
        $conn = db_connect();
        $orderInfo = null;

        $sqlOrder = "SELECT * FROM orders WHERE id = ? AND user_id = ?";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param("ii", $orderId, $userId);
        $stmtOrder->execute();
        $resultOrder = $stmtOrder->get_result();
        $orderInfo = $resultOrder->fetch_assoc();
        $stmtOrder->close();

        if ($orderInfo) {
            $sqlItems = "SELECT oi.quantity, oi.price, p.name, p.image_url 
                         FROM order_items oi
                         JOIN products p ON oi.product_id = p.id
                         WHERE oi.order_id = ?";
            $stmtItems = $conn->prepare($sqlItems);
            $stmtItems->bind_param("i", $orderId);
            $stmtItems->execute();
            $resultItems = $stmtItems->get_result();
            $orderInfo['items'] = $resultItems->fetch_all(MYSQLI_ASSOC);
            $stmtItems->close();
        }
        
        $conn->close();
        return $orderInfo;
    }

    /**
     * Cho phép người dùng hủy đơn hàng của chính họ.
     * @param int $orderId
     * @param int $userId
     * @return bool
     */
            public function cancelOrder($orderId, $userId) {
            $conn = db_connect();
            // Chỉ cho phép hủy khi trạng thái là 'Chờ Xác nhận'
            $sql = "UPDATE orders SET status = 'Đã hủy' WHERE id = ? AND user_id = ? AND status = 'Chờ Xác nhận'";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $orderId, $userId);
            $stmt->execute();
            $affected_rows = $stmt->affected_rows;
            $stmt->close();
            $conn->close();
            return $affected_rows > 0;
        }

    //

    /**
     * Kiểm tra xem một người dùng đã mua một sản phẩm cụ thể hay chưa.
     * @param int $userId
     * @param int $productId
     * @return bool
     */
    public function hasUserPurchasedProduct($userId, $productId) {
        $conn = db_connect();
        // Tìm kiếm trong order_items xem có sự kết hợp của user, product và đơn hàng đã hoàn thành không
        $sql = "SELECT oi.id 
                FROM order_items oi
                JOIN orders o ON oi.order_id = o.id
                WHERE o.user_id = ? AND oi.product_id = ? AND o.status = 'completed'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        $conn->close();
        return $num_rows > 0;
    }
}
?>