<?php
// File: project/includes/controllers/ProductController.php

require_once __DIR__ . '/../../models/ProductModel.php';

class ProductController
{
    public $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    /**
     * Hàm để lấy dữ liệu cho trang chủ.
     * @return array Dữ liệu sản phẩm.
     */
    public function getProductsForHomePage()
    {
        // Gọi model để lấy tất cả sản phẩm
        $products = $this->productModel->getAllActiveProducts();
        return $products;
    }

    //

    /**
     * Lấy dữ liệu cho trang chi tiết sản phẩm.
     * @return array|null Dữ liệu sản phẩm hoặc null nếu không tìm thấy.
     */
    public function showProductDetail()
    {
        // Lấy ID từ URL, ví dụ: product.php?id=1
        // Kiểm tra xem 'id' có tồn tại và có phải là số không
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $productId = intval($_GET['id']); // Chuyển đổi sang số nguyên để an toàn

            // Gọi model để tìm sản phẩm
            $product = $this->productModel->findProductById($productId);

            return $product;
        } else {
            // Nếu không có ID hoặc ID không hợp lệ, trả về null
            return null;
        }
    }

    //

    /**
     * Lấy danh sách sản phẩm cho trang admin.
     * @return array
     */
    public function listProductsForAdmin()
    {
        return $this->productModel->getAllProductsForAdmin();
    }

    /**
     * Xử lý việc thêm/sửa sản phẩm.
     */
    public function handleSaveProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Lấy dữ liệu từ form
            $data = [
                'name'        => trim($_POST['name']),
                'brand'       => trim($_POST['brand']),
                'description' => trim($_POST['description']),
                'price'       => floatval($_POST['price']),
                'stock'       => intval($_POST['stock']),
                'category_id' => intval($_POST['category_id']),
                'image_url'   => trim($_POST['image_url']),
                'status'      => $_POST['status'] ?? 'active' // Chỉ có khi sửa
            ];

            $productId = $_POST['product_id'] ?? null;

            if ($productId) {
                // Nếu có ID -> Cập nhật
                $this->productModel->updateProduct($productId, $data);
            } else {
                // Nếu không có ID -> Tạo mới
                $this->productModel->createProduct($data);
            }

            // Chuyển hướng về trang quản lý sản phẩm
            header("Location: ". BASE_URL . 'public/admin/products.php');
            exit();
        }
    }

    //

    /**
     * Xử lý yêu cầu tìm kiếm sản phẩm.
     * @return array
     */
    public function handleSearch()
    {
        $products = [];
        $keyword = '';
        if (isset($_GET['keyword'])) {
            $keyword = trim($_GET['keyword']);
            if (!empty($keyword)) {
                $products = $this->productModel->searchProducts($keyword);
            }
        }
        // Trả về cả sản phẩm và từ khóa để hiển thị lại trên trang
        return ['products' => $products, 'keyword' => $keyword];
    }

    /**
     * Xử lý xóa sản phẩm.
     */
    public function handleDeleteProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $productId = intval($_POST['product_id']);
            $success = $this->productModel->deleteProduct($productId);

            if ($success) {
                $_SESSION['success_message'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['error_message'] = 'Xóa sản phẩm thất bại!';
            }

            header("Location: ". BASE_URL . 'public/admin/products.php');
            exit();
        }
    }
}
