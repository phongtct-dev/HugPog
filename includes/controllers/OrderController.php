<?php
// File: project/includes/controllers/OrderController.php

require_once __DIR__ . '/../../models/OrderModel.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/voucherModel.php';




class OrderController
{
    private $productModel;
    private $voucherModel;
    private $cartModel;

    public function __construct()
    {
        // ...
        $this->productModel = new ProductModel();
        $this->voucherModel = new VoucherModel();
        $this->cartModel = new CartModel();
    }
    /**
     * Xử lý việc tạo đơn hàng.
     */
    public function handlePlaceOrder()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'public/login.php');
            exit();
        }

        $userId = $_SESSION['user_id'];
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartItemsByUserId($userId);

        if (empty($cartItems)) {
            header('Location: ' . VIEW_URL . 'user/cart.php');
            exit();
        }

        $customerName = trim($_POST['full_name']);
        $shippingAddress = trim($_POST['address']);
        $phone = trim($_POST['phone']);

        // ✅ TÍNH TỔNG PHỤ
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $price_to_use = $item['discounted_price'] ?? $item['price'];
            $subtotal += $price_to_use * $item['quantity'];
        }

        // ✅ ÁP MÃ GIẢM GIÁ
        $voucher = $_SESSION['voucher'] ?? null;
        $voucherDiscount = 0;

        if ($voucher) {
            $discountValue = floatval($voucher['discount_value'] ?? 0);
            $discountType  = $voucher['type'] ?? $voucher['discount_type'] ?? 'amount';

            if ($discountType === 'percent') {
                $voucherDiscount = ($discountValue / 100) * $subtotal;
            } else {
                $voucherDiscount = $discountValue;
            }

            if ($voucherDiscount > $subtotal) {
                $voucherDiscount = $subtotal;
            }
        }

        // ✅ TỔNG THANH TOÁN CUỐI
        $totalAmount = $subtotal - $voucherDiscount;
        if ($totalAmount < 0) $totalAmount = 0;

        // ✅ LƯU ĐƠN HÀNG
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
    public function listOrdersForAdmin()
    {
        $orderModel = new OrderModel();
        return $orderModel->getAllOrders();
    }

    /**
     * Lấy chi tiết đơn hàng cho trang admin.
     * @return array|null
     */
    public function showOrderDetailForAdmin()
    {
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
    public function handleProcessOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['action'])) {
            $orderId = intval($_POST['order_id']);
            $action = $_POST['action'];

            $orderModel = new OrderModel();
            $order = $orderModel->getOrderDetailsById($orderId);

            if (!$order) {
                header('Location: ' . BASE_URL . 'public/admin/orders.php');
                exit();
            }

            $newStatus = $order['status'];

            if ($action === 'cancel') {
                $newStatus = 'Đã hủy';
            } elseif ($action === 'progress') {
                switch ($order['status']) {
                    case 'Chờ Xác nhận':
                        $newStatus = 'Đã Xác nhận';
                        break;
                    case 'Đã Xác nhận':
                        $newStatus = 'Đang giao';
                        break;
                    case 'Đang giao':
                        $newStatus = 'Đã giao';
                        break;
                    case 'Đã giao':
                        $newStatus = 'Thành công';
                        break;
                }
            }

            if ($newStatus !== $order['status']) {
                $orderModel->updateOrderStatus($orderId, $newStatus);

                // === TÍCH HỢP LOGIC CẬP NHẬT HẠNG KHÁCH HÀNG Ở ĐÂY ===
                if ($newStatus === 'Thành công') {
                    // Cần nạp UserModel để sử dụng
                    require_once __DIR__ . '/../../models/UserModel.php';
                    $userModel = new UserModel();
                    $userModel->updateUserSpendingAndRank($order['user_id'], $order['total_amount']);
                }
                // =======================================================
            }
        }
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }

    //

    /**
     * Lấy lịch sử đơn hàng cho người dùng đang đăng nhập.
     * @return array
     */
    public function showOrderHistory()
    {
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
    public function showUserOrderDetail()
    {
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            return null;
        }
        $orderModel = new OrderModel();
        return $orderModel->findUserOrderById(intval($_GET['id']), $_SESSION['user_id']);
    }

    /**
     * Xử lý yêu cầu hủy đơn hàng từ người dùng.
     */
    public function handleCancelOrder()
    {
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

    public function handleAjaxRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
            $orderId = intval($_POST['order_id']);
            $currentStatus = trim($_POST['current_status']);

            $orderModel = new OrderModel();
            $order = $orderModel->getOrderDetailsById($orderId);

            if (!$order) {
                echo json_encode(['success' => false, 'message' => 'Không tìm thấy đơn hàng.']);
                exit;
            }

            $info = $this->getNextStatusInfo($currentStatus);

            if (!$info['next_status']) {
                echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thêm trạng thái.']);
                exit;
            }

            $updated = $orderModel->updateOrderStatus($orderId, $info['next_status']);

            if ($updated) {
                // Nếu hoàn tất đơn hàng => cập nhật hạng người dùng
                if ($info['next_status'] === 'Thành công') {
                    $userModel = new UserModel();
                    $userModel->updateUserSpendingAndRank($order['user_id'], $order['total_amount']);
                }

                echo json_encode([
                    'success' => true,
                    'next_status' => $info['next_status'],
                    'next_action_text' => $info['next_action_text'],
                    'message' => 'Cập nhật trạng thái thành công.'
                ]);
                exit;
            } else {
                echo json_encode(['success' => false, 'message' => 'Cập nhật thất bại.']);
                exit;
            }
        }
    }

    /**
     * Helper để xác định trạng thái tiếp theo và tên hành động.
     * @param string $currentStatus
     * @return array
     */
    private function getNextStatusInfo($currentStatus)
    {
        $info = ['next_status' => null, 'next_action_text' => ''];

        switch ($currentStatus) {
            case 'Chờ Xác nhận':
                $info['next_status'] = 'Đã Xác nhận';
                $info['next_action_text'] = 'Chuyển sang Đang giao';
                break;
            case 'Đã Xác nhận':
                $info['next_status'] = 'Đang giao';
                $info['next_action_text'] = 'Đánh dấu Đã giao';
                break;
            case 'Đang giao':
                $info['next_status'] = 'Đã giao';
                $info['next_action_text'] = 'Hoàn tất đơn hàng';
                break;
            case 'Đã giao':
                $info['next_status'] = 'Thành công';
                $info['next_action_text'] = 'Đã hoàn tất';
                break;
            default:
                break;
        }
        return $info;
    }

    /**
     * Lấy dữ liệu giỏ hàng, bao gồm giá cuối cùng sau khuyến mãi sản phẩm.
     * @return array Mảng chứa 'items' và 'subtotal'
     */
    public function getCartItems()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $finalItems = [];
        $subtotal = 0;

        // 1. Lấy dữ liệu thô từ DB (người dùng đã đăng nhập)
        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartRaw = $this->cartModel->getCartItemsByUserId($userId); // Giả định hàm này lấy ID và Quantity

            foreach ($cartRaw as $item) {
                // Lấy thông tin giá chi tiết và khuyến mãi từ ProductModel
                // Giả định findProductById trong ProductModel trả về price_final và discount
                $productDetails = $this->productModel->findProductById($item['product_id']);

                if ($productDetails) {
                    $item['id'] = $item['product_id']; // Đảm bảo có ID sản phẩm
                    $item['name'] = $productDetails['name'];
                    $item['image'] = $productDetails['image_url'];
                    $item['quantity'] = $item['quantity'];

                    // LẤY GIÁ SAU KHI ÁP DỤNG KHUYẾN MÃI SẢN PHẨM (PromotionModel)
                    $item['price_original'] = $productDetails['price'];
                    $item['price_final'] = $productDetails['discounted_price'] ?? $productDetails['price'];
                    $item['discount'] = $productDetails['discount_percent'] ?? 0;

                    $item['total'] = $item['quantity'] * $item['price_final'];
                    $subtotal += $item['total'];
                    $finalItems[] = $item;
                }
            }
            // 2. Lấy dữ liệu từ SESSION (khách chưa đăng nhập)
        } else if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $finalItems = $_SESSION['cart'];
            foreach ($finalItems as $item) {
                $item['total'] = $item['quantity'] * $item['price_final'];
                $subtotal += $item['total'];
            }
        }

        return ['items' => $finalItems, 'subtotal' => $subtotal];
    }


    /**
     * Tính toán tổng tiền cuối cùng sau khi áp dụng Voucher.
     * @param float $subtotal Tổng tiền trước khi áp dụng voucher và phí ship.
     * @return array Mảng chứa 'subtotal', 'shippingFee', 'totalAmount', 'voucherDiscount'
     */
    public function calculateCartTotals($subtotal)
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $voucherDiscount = 0;
        $voucherCode = $_SESSION['voucher']['code'] ?? null;

        if ($voucherCode) {

            // Tái kiểm tra voucher từ DB để đảm bảo tính hợp lệ (UserModel đã có findActiveVoucherByCode)
            $voucher = $this->voucherModel->getVoucherByCode($voucherCode);

            if ($voucher && $subtotal >= $voucher['min_order_amount']) {
                if ($voucher['discount_type'] === 'fixed') {
                    $voucherDiscount = $voucher['discount_value'];
                } elseif ($voucher['discount_type'] === 'percent') {
                    $discountPercent = $voucher['discount_value'] / 100;
                    $calculatedDiscount = $subtotal * $discountPercent;
                    // Giới hạn mức giảm tối đa nếu có
                    $voucherDiscount = min($calculatedDiscount, $voucher['max_discount_amount']);
                }
            } else {
                // Nếu voucher không còn hợp lệ, xóa khỏi session
                unset($_SESSION['voucher']);
                $voucherDiscount = 0;
            }
        }

        // Tổng tiền sau khi giảm voucher
        $totalAfterVoucher = max(0, $subtotal - $voucherDiscount);

        // Tổng tiền cuối cùng
        $totalAmount = $totalAfterVoucher;

        return [
            'subtotal' => $subtotal,
            'voucherDiscount' => $voucherDiscount,
            'totalAmount' => $totalAmount,
        ];
    }
}

// Nếu file này được gọi trực tiếp (qua AJAX):
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new OrderController();
    $controller->handleAjaxRequest();
}
