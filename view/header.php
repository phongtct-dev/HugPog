<?php
require_once __DIR__ . '/../includes/config.php';
// Nhúng CartController để lấy số lượng
require_once __DIR__ . '/../includes/controllers/CartController.php';

$cartController = new CartController();
$cart_count = $cartController->getCartCountForHeader();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK-Store | Phụ kiện điện tử</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo BASE_URL; ?>public/index.php">
            <i class="fas fa-microchip"></i> PK-Store
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            
            <form class="d-flex mx-auto" action="<?php echo BASE_URL; ?>public/search.php" method="GET">
                <input class="form-control me-2" type="search" name="keyword" placeholder="Tìm kiếm sản phẩm..." aria-label="Search">
                <button class="btn btn-outline-light" type="submit">Tìm</button>
            </form>
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>public/index.php">
                        <i class="fas fa-shopping-cart"></i> Trang chủ 
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL; ?>public/cart.php">
                        <i class="fas fa-shopping-cart"></i> Giỏ hàng 
                        <span class="badge bg-danger"><?php echo $cart_count; ?></span>
                    </a>
                </li>
                
                
                <?php if (isset($_SESSION['user_id'])): // Nếu người dùng ĐÃ đăng nhập ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i> Chào, <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">

                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>public/order_history.php">Lịch sử mua hàng</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_URL; ?>public/logout.php">Đăng xuất</a></li>
                            
                        </ul>
                    </li>
                <?php else: // Nếu người dùng CHƯA đăng nhập ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>public/login.php">Đăng nhập</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>public/register.php">Đăng ký</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container mt-4">