<?php
// File: project/public/index.php (Phiên bản Tìm kiếm Nâng cao)

require_once '../includes/controllers/ProductController.php';
require_once '../includes/controllers/CategoryController.php';

$productController = new ProductController();
$categoryController = new CategoryController();

// 1. Luôn lấy danh sách danh mục và thương hiệu để hiển thị form bộ lọc
$categories = $categoryController->listCategories();
$brands = $productController->productModel->getDistinctBrands();

// 2. Lấy các giá trị lọc từ URL mà người dùng gửi lên (qua method GET)
$filters = [
    'categories' => $_GET['categories'] ?? [], // Lấy mảng các danh mục
    'max_price'  => $_GET['max_price'] ?? null,   // Lấy giá tối đa
    'brands'     => $_GET['brands'] ?? []      // Lấy mảng các thương hiệu
];

// 3. Gọi hàm filterProducts trong Model để lấy sản phẩm theo đúng bộ lọc
$products = $productController->productModel->filterProducts($filters);

// 4. Nạp file giao diện và truyền tất cả dữ liệu cần thiết qua
include '../view/user/product_list.php';
?>