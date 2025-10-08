<?php
require_once '../includes/controllers/ProductController.php';
require_once '../models/OrderModel.php';     

$productController = new ProductController();
$order_model = new OrderModel();


// 3. Lấy dữ liệu cần thiết cho trang chủ
// Giả định hàm này lấy tất cả sản phẩm đang hoạt động, đã được tính toán giá giảm
// (như được định nghĩa trong ProductController và ProductModel).
$allProduct = $productController->getProductsForHomePage();

// 5. Logic phân chia sản phẩm (để hiển thị theo cột trong giao diện)
// Giả định muốn hiển thị tối đa 16 sản phẩm (4 hàng x 4 cột)
$bestSellers = array_slice($allProduct, 0, 4);
// Số sản phẩm muốn hiển thị trong mỗi cột của khối "Sản phẩm bán chạy"
$productsPerBlock = ceil(count($bestSellers) / 4);
$productBlocks = array_chunk($bestSellers, 1);
$top_products = $order_model->getTopSellingProducts(4) ?? [];

include '../view/user/index.php';
?>