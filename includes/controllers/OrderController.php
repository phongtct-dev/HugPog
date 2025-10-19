<?php

namespace App\Controllers;
// File: project/includes/controllers/OrderController.php

require_once __DIR__ . '/../config.php';

use App\Models\OrderModel;
use App\Models\CartModel;
use App\Models\ProductModel;
use App\Models\VoucherModel;
use App\Models\UserModel;



// === THÊM 2 DÒNG NÀY VÀO ĐỂ GỬI GMAIL===
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// =============================


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

    // --- BẮT ĐẦU PHẦN SỬA LỖI LOGIC ---

    // 1. Khởi tạo CartController để sử dụng lại logic tính toán
    $cartController = new CartController();

    // 2. Lấy thông tin giỏ hàng (giống hệt trang checkout)
    $cartData = $cartController->getCartItems();
    $cartItems = $cartData['items'];
    $subtotal = $cartData['subtotal'];

    if (empty($cartItems)) {
        header('Location: ' . BASE_URL . 'public/cart.php');
        exit();
    }

    // 3. Tính toán lại tổng tiền cuối cùng một cách CHÍNH XÁC (giống hệt trang checkout)
    $totalsData = $cartController->calculateCartTotals($subtotal);
    $voucherDiscount = $totalsData['voucherDiscount']; // Số tiền giảm giá đúng (VD: 110.000)
    $totalAmount = $totalsData['totalAmount'];       // Tổng tiền cuối cùng đúng (VD: 440.000)

    // --- KẾT THÚC PHẦN SỬA LỖI LOGIC ---

    // Lấy thông tin khách hàng từ form
    $customerName = trim($_POST['full_name']);
    $shippingAddress = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $voucher = $_SESSION['voucher'] ?? null;

    // 4. Gọi Model để lưu đơn hàng với các con số ĐÃ ĐÚNG
    $orderModel = new OrderModel();
    $orderId = $orderModel->createOrder($userId, $customerName, $shippingAddress, $phone, $cartItems, $totalAmount, $voucher, $voucherDiscount);

    if ($orderId) {
        unset($_SESSION['voucher']);

        // Gọi hàm gửi email hóa đơn
        $this->sendOrderConfirmationEmail($orderId);

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

    /**
     * Soạn và gửi email xác nhận đơn hàng (hóa đơn) cho khách hàng.
     * @param int $orderId Mã đơn hàng vừa tạo.
     * @return bool True nếu gửi thành công, false nếu thất bại.
     */
    private function sendOrderConfirmationEmail($orderId)
    {
        $orderModel = new OrderModel();
        // Lấy thông tin chi tiết đơn hàng, bao gồm cả sản phẩm
        $order = $orderModel->getOrderDetailsById($orderId);

        if (!$order) {
            error_log("Gửi mail thất bại: Không tìm thấy đơn hàng ID " . $orderId);
            return false;
        }

        $userModel = new UserModel();
        // Lấy thông tin người dùng để có email
        $user = $userModel->getUserById($order['user_id']);
        if (!$user) {
            error_log("Gửi mail thất bại: Không tìm thấy người dùng cho đơn hàng ID " . $orderId);
            return false;
        }

        // Bắt đầu soạn nội dung email (HTML)
        $emailBody = "<h1>Xác nhận đơn hàng #" . htmlspecialchars($orderId) . "</h1>";
        $emailBody .= "<p>Xin chào " . htmlspecialchars($order['customer_name']) . ",</p>";
        $emailBody .= "<p>Cảm ơn bạn đã đặt hàng tại HugPog Store. Dưới đây là chi tiết đơn hàng của bạn:</p>";
        $emailBody .= "<table border='1' cellpadding='10' cellspacing='0' style='width: 100%; border-collapse: collapse; border-color: #ddd;'>";
        $emailBody .= "<thead><tr style='background-color: #f2f2f2;'><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead>";
        $emailBody .= "<tbody>";

        foreach ($order['items'] as $item) {
            $emailBody .= "<tr>";
            $emailBody .= "<td>" . htmlspecialchars($item['name']) . "</td>";
            $emailBody .= "<td style='text-align: center;'>" . $item['quantity'] . "</td>";
            $emailBody .= "<td style='text-align: right;'>" . number_format($item['price'], 0, ',', '.') . " VNĐ</td>";
            $emailBody .= "<td style='text-align: right;'>" . number_format($item['price'] * $item['quantity'], 0, ',', '.') . " VNĐ</td>";
            $emailBody .= "</tr>";
        }

        $emailBody .= "</tbody></table>";
        $emailBody .= "<p style='text-align: right; margin-top: 15px;'><strong>Tổng cộng: " . number_format($order['total_amount'], 0, ',', '.') . " VNĐ</strong></p>";
        $emailBody .= "<p>Chúng tôi sẽ xử lý và giao đơn hàng của bạn đến địa chỉ: " . htmlspecialchars($order['shipping_address']) . " trong thời gian sớm nhất.</p>";
        $emailBody .= "<p>Trân trọng,<br>Đội ngũ HugPog</p>";

        // Bắt đầu gửi email
        $mail = new PHPMailer(true);
        try {
            // Cấu hình SMTP (sao chép từ UserController và điền thông tin)
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'tranphong1318@gmail.com'; // << THAY EMAIL CỦA 
            $mail->Password   = 'hjau zrbz ykza gsea';    // << THAY MẬT KHẨU ỨNG DỤNG
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            // Người gửi và người nhận
            $mail->setFrom('your_email@gmail.com', 'HugPog Store');
            $mail->addAddress($user['email'], $order['customer_name']);

            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = 'Xac nhan don hang #' . $orderId . ' tu HugPog Store';
            $mail->Body    = $emailBody;

            $mail->send();
            return true;
        } catch (\Exception $e) {
            // Ghi log lỗi để bạn có thể xem lại nếu có vấn đề, không hiển thị cho người dùng
            error_log("Lỗi gửi email hóa đơn #" . $orderId . ": " . $mail->ErrorInfo);
            return false;
        }
    }
}

// Nếu file này được gọi trực tiếp (qua AJAX):
if (basename(__FILE__) === basename($_SERVER['SCRIPT_FILENAME'])) {
    $controller = new OrderController();
    $controller->handleAjaxRequest();
}
