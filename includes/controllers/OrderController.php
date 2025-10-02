<?php
// File: project/includes/controllers/OrderController.php

require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/CartModel.php';

class OrderController {

    /**
     * Xử lý việc tạo đơn hàng.
     */
        public function handlePlaceOrder() {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'public/login.php');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItemsByUserId($userId);

        if (empty($cartItems)) {
            header('Location: ' . BASE_URL . 'public/cart.php');
            exit();
        }

        $customerName = trim($_POST['full_name']);
        $shippingAddress = trim($_POST['address']);
        $phone = trim($_POST['phone']);

        $voucher = $_SESSION['voucher'] ?? null;
        $voucherDiscount = $voucher['discount_value'] ?? 0;

        // Tính toán lại tổng tiền dựa trên giá có thể đã được khuyến mãi
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price_to_use = $item['discounted_price'] ?? $item['price'];
            $subtotal += $price_to_use * $item['quantity'];
        }
        $totalAmount = $subtotal - $voucherDiscount;

        $orderModel = new OrderModel();
        $orderId = $orderModel->createOrder($userId, $customerName, $shippingAddress, $phone, $cartItems, $totalAmount, $voucher);

        if ($orderId) {
            unset($_SESSION['voucher']);
            header('Location: ' . BASE_URL . 'public/order_success.php?order_id=' . $orderId);
            exit();
        } else {
            $_SESSION['error_message'] = "Đặt hàng thất bại. Vui lòng thử lại.";
            header('Location: ' . BASE_URL . 'public/checkout.php');
            exit();
        }
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
     * Xử lý việc cập nhật trạng thái đơn hàng theo quy trình (tiến trình hoặc hủy).
     */
    public function handleProcessOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
            $orderId = intval($_POST['order_id']);
            $action = $_POST['action'];
            
            $orderModel = new OrderModel();
            $order = $orderModel->getOrderDetailsById($orderId);

            if (!$order) {
                // Không tìm thấy đơn hàng, không làm gì cả
                header('Location: ' . BASE_URL . 'public/admin/orders.php');
                exit();
            }

            $newStatus = $order['status']; // Mặc định là trạng thái cũ

            if ($action === 'cancel') {
                $newStatus = 'cancelled';
            } elseif ($action === 'progress') {
                // Xác định trạng thái tiếp theo dựa trên trạng thái hiện tại
                switch ($order['status']) {
                    case 'pending':
                        $newStatus = 'confirmed';
                        break;
                    case 'confirmed':
                        $newStatus = 'shipping';
                        break;
                    case 'shipping':
                        $newStatus = 'delivered';
                        break;
                    case 'delivered':
                        $newStatus = 'completed';
                        break;
                    // Nếu là 'completed' hoặc 'cancelled' thì không làm gì cả
                }
            }
            
            // Chỉ cập nhật nếu trạng thái có thay đổi
            if ($newStatus !== $order['status']) {
                $orderModel->updateOrderStatus($orderId, $newStatus);
            }
        }
        // Luôn chuyển hướng về lại trang chi tiết đơn hàng
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    //

    /**
     * Lấy lịch sử đơn hàng cho người dùng đang đăng nhập.
     * @return array
     */
    public function showOrderHistory() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . 'public/login.php');
            exit();
        }
        $orderModel = new OrderModel();
        return $orderModel->getOrdersByUserId($_SESSION['user_id']);
    }
    
    /**
     * Lấy chi tiết một đơn hàng cho người dùng.
     * @return array|null
     */
    public function showUserOrderDetail() {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            return null;
        }
        $orderModel = new OrderModel();
        return $orderModel->findUserOrderById(intval($_GET['id']), $_SESSION['user_id']);
    }

    /**
     * Xử lý yêu cầu hủy đơn hàng từ người dùng.
     */
    public function handleCancelOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['order_id'])) {
            $orderModel = new OrderModel();
            $success = $orderModel->cancelOrder(intval($_POST['order_id']), $_SESSION['user_id']);
            // Thêm thông báo vào session để hiển thị
            if ($success) {
                $_SESSION['success_message'] = "Đơn hàng đã được hủy thành công.";
            } else {
                $_SESSION['error_message'] = "Không thể hủy đơn hàng này hoặc đơn hàng đã được xử lý.";
            }
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
}
?>