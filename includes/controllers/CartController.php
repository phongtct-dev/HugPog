<?php
// File: project/includes/controllers/CartController.php (Phiên bản sửa lỗi)

require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/VoucherModel.php';

class CartController {
    private $cartModel;

    public function __construct() {
        // Luôn đảm bảo config được nạp để có BASE_URL
        require_once __DIR__ . '/../config.php';
        $this->cartModel = new CartModel();
    }

    /**
     * Xử lý việc thêm sản phẩm vào giỏ hàng.
     */
    public function handleAddToCart() {
        // Bước 1: Kiểm tra người dùng đã đăng nhập chưa
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vui lòng đăng nhập để sử dụng giỏ hàng!";
            // Sử dụng BASE_URL từ config để đảm bảo đường dẫn luôn đúng
            header('Location: ' . BASE_URL . 'public/login.php');
            exit();
        }

        // Bước 2: Kiểm tra đây có phải là request POST và có product_id không
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['product_id'])) {
            // Nếu không hợp lệ, chuyển về trang chủ
            header('Location: ' . BASE_URL . 'public/index.php');
            exit();
        }

        // Bước 3: Lấy dữ liệu và xử lý
        $userId = $_SESSION['user_id'];
        $productId = intval($_POST['product_id']);
        $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

        if ($quantity > 0 && $productId > 0) {
            // Gọi Model để thêm/cập nhật vào DB
            $this->cartModel->upsertItem($userId, $productId, $quantity);
        }
        
        // Bước 4: Chuyển hướng người dùng về trang họ vừa thao tác
        // Nếu không có HTTP_REFERER, chuyển về trang chủ
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/index.php');
        header('Location: ' . $redirectUrl);
        exit();
    }
    
    // Hàm getCartCountForHeader giữ nguyên như cũ
    public function getCartCountForHeader() {
        if (isset($_SESSION['user_id'])) {
            return $this->cartModel->getCartItemCount($_SESSION['user_id']);
        }
        return 0;
    }


    //

    /**
     * Lấy dữ liệu để hiển thị trang giỏ hàng.
     * @return array
     */
    public function showCartPage() {
        if (!isset($_SESSION['user_id'])) {
            return []; // Trả về mảng rỗng nếu chưa đăng nhập
        }
        return $this->cartModel->getCartItemsByUserId($_SESSION['user_id']);
    }

    /**
     * Xử lý việc cập nhật số lượng sản phẩm.
     */
    public function handleUpdateCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $productId = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity']);
            $userId = $_SESSION['user_id'];

            if ($productId > 0) {
                if ($quantity > 0) {
                    // Cập nhật số lượng
                    $this->cartModel->updateItemQuantity($userId, $productId, $quantity);
                } else {
                    // Nếu số lượng là 0 hoặc ít hơn, xóa sản phẩm
                    $this->cartModel->removeItem($userId, $productId);
                }
            }
        }
        // Chuyển hướng về lại trang giỏ hàng
        header('Location: ' . BASE_URL . 'public/cart.php');
        exit();
    }
    
    /**
     * Xử lý việc xóa sản phẩm khỏi giỏ hàng.
     */
    public function handleRemoveItem() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
            $productId = intval($_POST['product_id']);
            $userId = $_SESSION['user_id'];

            if ($productId > 0) {
                $this->cartModel->removeItem($userId, $productId);
            }
        }
        header('Location: ' . BASE_URL . 'public/cart.php');
        exit();
    }

    /**
     * Xử lý việc áp dụng mã voucher.
     */
    public function handleApplyVoucher() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voucher_code'])) {
            $code = trim($_POST['voucher_code']);
            
            if (empty($code)) {
                $_SESSION['voucher_error'] = "Vui lòng nhập mã voucher.";
            } else {
                $voucherModel = new VoucherModel();
                $voucher = $voucherModel->findVoucherByCode($code);
                
                if ($voucher) {
                    // Nếu tìm thấy voucher hợp lệ, lưu vào session
                    $_SESSION['voucher'] = [
                        'id' => $voucher['id'],
                        'code' => $voucher['code'],
                        'discount_value' => $voucher['discount_value']
                    ];
                    $_SESSION['voucher_success'] = "Áp dụng voucher thành công!";
                } else {
                    // Không tìm thấy hoặc voucher không hợp lệ
                    $_SESSION['voucher_error'] = "Mã voucher không hợp lệ hoặc đã hết hạn.";
                    unset($_SESSION['voucher']); // Xóa voucher cũ nếu có
                }
            }
        }
        header('Location: ' . BASE_URL . 'public/cart.php');
        exit();
    }

    /**
     * Xử lý việc xóa mã voucher đã áp dụng.
     */
    public function handleRemoveVoucher() {
        if (isset($_SESSION['voucher'])) {
            unset($_SESSION['voucher']);
        }
        header('Location: ' . BASE_URL . 'public/cart.php');
        exit();
    }

}