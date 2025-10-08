<?php
// File: project/includes/controllers/CartController.php (Phiên bản đã sửa)
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../../models/CartModel.php';
require_once __DIR__ . '/../../models/VoucherModel.php';
require_once __DIR__ . '/../../models/ProductModel.php';
require_once __DIR__ . '/../../models/OrderModel.php';

class CartController
{
    private $cartModel;
    private $voucherModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new CartModel();
        $this->voucherModel = new VoucherModel();
        $this->productModel = new ProductModel();
    }

    public function getCartCountForHeader()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->cartModel->getCartItemCount($_SESSION['user_id']);
        }
        return 0;
    }

    public function showCartPage()
    {
        if (!isset($_SESSION['user_id'])) {
            return [];
        }
        return $this->cartModel->getCartItemsByUserId($_SESSION['user_id']);
    }

    /**
     * Xử lý việc cập nhật số lượng sản phẩm cho nhiều sản phẩm từ form.
     */
    public function handleUpdateCart()
    {
        // Bảo vệ: chỉ xử lý khi POST + user login + có mảng quantities
        // Thêm kiểm tra $_POST['update_cart'] nếu bạn có nút submit cụ thể cho hành động này
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            isset($_SESSION['user_id'], $_POST['quantities'])
        ) {
            $userId = $_SESSION['user_id'];
            $quantities = $_POST['quantities']; // Mảng: [product_id => quantity]

            foreach ($quantities as $productId => $quantity) {
                $productId = intval($productId);
                $quantity = intval($quantity);

                // Kiểm tra ID sản phẩm phải hợp lệ
                if ($productId > 0) {
                    if ($quantity > 0) {
                        // Cập nhật số lượng sản phẩm trong CSDL (hoặc Session)
                        // Hàm này đảm bảo số lượng mới được lưu lại.
                        $this->cartModel->updateItemQuantity($userId, $productId, $quantity);
                    } else {
                        // Nếu số lượng <= 0, xóa sản phẩm khỏi giỏ hàng
                        $this->cartModel->removeItem($userId, $productId);
                    }
                }
            }
        }
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/product_list.php');
        header('Location: ' . $redirectUrl);
        exit();
    }

    /**
     * Xử lý việc xóa sản phẩm khỏi giỏ hàng.
     */
    public function handleRemoveItem()
    {
        // Bảo vệ: chỉ xử lý khi POST + user login + product_id tồn tại
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'], $_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            $userId = $_SESSION['user_id'];

            if ($productId > 0) {
                $this->cartModel->removeItem($userId, $productId);
            }
        }
        $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/product_list.php');
        header('Location: ' . $redirectUrl);
        exit();
    }

    public function handleApplyVoucher()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['voucher_code'])) {
            $code = trim($_POST['voucher_code']);

            if (empty($code)) {
                $_SESSION['voucher_error'] = "Vui lòng nhập mã voucher.";
            } else {
                $voucherModel = new VoucherModel();
                $voucher = $voucherModel->findVoucherByCode($code);

                if ($voucher) {
                    $_SESSION['voucher'] = [
                        'id' => $voucher['id'],
                        'code' => $voucher['code'],
                        'discount_value' => $voucher['discount_value']
                    ];
                    $_SESSION['voucher_success'] = "Áp dụng voucher thành công!";
                } else {
                    $_SESSION['voucher_error'] = "Mã voucher không hợp lệ hoặc đã hết hạn.";
                    unset($_SESSION['voucher']);
                }
            }
        }
         $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/product_list.php');
        header('Location: ' . $redirectUrl);
        exit();
    }

    public function handleRemoveVoucher()
    {
        if (isset($_SESSION['voucher'])) {
            unset($_SESSION['voucher']);
        }
         $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/product_list.php');
        header('Location: ' . $redirectUrl);
        exit();
    }

    public function getCartQuantity()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->cartModel->getTotalQuantity($_SESSION['user_id']);
        }
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            return array_sum(array_column($_SESSION['cart'], 'quantity'));
        }
        return 0;
    }

    public function getCartItems()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();
        $finalItems = [];
        $subtotal = 0;

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $cartRaw = $this->cartModel->getCartItemsByUserId($userId);

            foreach ($cartRaw as $item) {
                $productDetails = $this->productModel->findProductById($item['id']);
                if ($productDetails) {
                    $item['id'] = $item['id'];
                    $item['name'] = $productDetails['name'];
                    $item['image'] = $productDetails['image_url'] ?? '';
                    $item['quantity'] = $item['quantity'];

                    $item['price_original'] = $productDetails['price'];
                    $item['price_final'] = $productDetails['discounted_price'] ?? $productDetails['price'];
                    $item['discount'] = $productDetails['discount_percent'] ?? 0;

                    $item['total'] = $item['quantity'] * $item['price_final'];
                    $subtotal += $item['total'];
                    $finalItems[] = $item;
                }
            }
        } else if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            $finalItems = $_SESSION['cart'];
            foreach ($finalItems as &$item) {
                $item['total'] = $item['quantity'] * ($item['price_final'] ?? $item['price']);
                $subtotal += $item['total'];
            }
            unset($item);
        }

        return ['items' => $finalItems, 'subtotal' => $subtotal];
    }

    public function calculateCartTotals($subtotal)
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $voucherDiscount = 0;
        // Lấy mã voucher đã áp dụng từ session
        $voucherCode = $_SESSION['voucher']['code'] ?? null;

        if ($voucherCode) {
            // Lấy thông tin chi tiết voucher từ Model
            // Lưu ý: Để voucher hoạt động, hàm getVoucherByCode() phải trả về voucher HỢP LỆ (còn hạn, còn số lượng, active).
            $voucher = $this->voucherModel->getVoucherByCode($voucherCode);

            // **LOGIC ÁP DỤNG VOUCHER ĐÃ SỬA**
            if ($voucher) {
                $discountValue = $voucher['discount_value'];

                // 1. Áp dụng quy tắc suy đoán loại giảm giá:
                //    - Nếu giá trị <= 100 (và > 0): Giảm theo Phần trăm.
                //    - Nếu giá trị > 100: Giảm Tiền cố định (VNĐ).

                if ($discountValue <= 100 && $discountValue > 0) {
                    // Loại Giảm theo Phần trăm (Percent)
                    $discountPercent = $discountValue / 100;
                    $voucherDiscount = $subtotal * $discountPercent;

                    // Do không có cột max_discount_amount, không thể áp dụng giới hạn giảm tối đa.

                } elseif ($discountValue > 100) {
                    // Loại Giảm Tiền cố định (Fixed Amount)
                    $voucherDiscount = $discountValue;
                }

                // 2. Đảm bảo số tiền giảm không vượt quá tổng tiền hàng (để tổng tiền không âm)
                $voucherDiscount = min($voucherDiscount, $subtotal);
            } else {
                // Voucher không tồn tại hoặc không hợp lệ, xóa khỏi session
                unset($_SESSION['voucher']);
            }
        }

        // Tính tổng tiền cuối cùng: Tổng phụ - Giảm giá Voucher + Phí vận chuyển
        $totalAmount = max(0, $subtotal - $voucherDiscount); // max(0, ...) để tránh tổng tiền bị âm

        return [
            'voucherDiscount' => $voucherDiscount,
            'totalAmount' => $totalAmount,
        ];
    }

    private function getProductDetailsForCart($productId)
    {
        $product = $this->productModel->findProductById($productId);

        if ($product) {
            $product['price_original'] = $product['price'];
            $product['price_final'] = $product['discounted_price'] ?? $product['price'];
            $product['discount'] = $product['discount_percent'] ?? 0;
            $product['image'] = $product['image_url'] ?? '';
        }
        return $product;
    }

    /**
     * Xử lý việc thêm sản phẩm vào giỏ hàng.
     */
    public function handleAddToCart()
    {
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
            header('Location: ' . BASE_URL . 'public/product_list.php');
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
            $redirectUrl = $_SERVER['HTTP_REFERER'] ?? (BASE_URL . 'public/product_list.php');
            header('Location: ' . $redirectUrl);
        exit();
    }
}
