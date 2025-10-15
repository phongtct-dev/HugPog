<?php
// FILE: view/layout/header.php (PHIÊN BẢN ĐÃ SỬA)
// File này bây giờ chỉ chứa HTML và hiển thị các biến đã được chuẩn bị sẵn.
// Toàn bộ logic require_once và khởi tạo Controller đã được chuyển ra ngoài.
?>

<header>
    <div id="top-header">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa-solid fa-phone"></i> +021-95-51-84</a></li>
                <li><a href="#"><i class="fa-regular fa-envelope"></i> email@email.com</a></li>
                <li><a href="https://www.google.com/maps/place/613+%C4%90.+%C3%82u+C%C6%A1,+Ph%C3%BA+Trung,+T%C3%A2n+Ph%C3%BA,+H%E1%BB%93+Ch%C3%AD+Minh+700000,+Vi%E1%BB%87t+Nam/@10.7843347,106.6390851,17z/data=!3m1!4b1!4m6!3m5!1s0x31752eb1cd7d4e49:0x411ab56b2abeaf38!8m2!3d10.7843294!4d106.64166!16s%2Fg%2F11lnh_1llp?entry=ttu&g_ep=EgoyMDI1MDkzMC4wIKXMDSoASAFQAw%3D%3D"><i class="fa-solid fa-location-dot"></i> 613 Âu Cơ</a></li>
            </ul>
            <ul class="header-links pull-right">
                <li><a href="#"><i class="fa-solid fa-dollar-sign"></i> VND</a></li>
                <?php if ($is_logged_in): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: white;">
                            <i class="fa fa-user-o"></i> Xin chào, <?php echo htmlspecialchars($logged_in_username); ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end p-2" aria-labelledby="userDropdown" style="min-width: 220px">
                            <a class="dropdown-item d-flex align-items-center gap-2 text-dark" href="<?php echo BASE_URL; ?>public/profile.php">
                                <i class="fa-solid fa-user-circle text-secondary"></i>
                                <span>Hồ sơ của tôi</span>
                            </a>

                            <a class="dropdown-item d-flex align-items-center gap-2 text-dark" href="<?php echo BASE_URL; ?>public/order_history.php">
                                <i class="fa-solid fa-list-check text-secondary"></i>
                                <span>Lịch sử đơn hàng</span>
                            </a>
                            <a class="dropdown-item d-flex align-items-center gap-2 text-dark" href="<?php echo BASE_URL; ?>public/logout.php">
                                <i class="fa fa-sign-out me-2"></i> Đăng xuất
                            </a>
                        </div>
                    </li>
                <?php else: ?>
                    <li><a href="<?php echo BASE_URL; ?>public/login.php"><i class="fa fa-user-o"></i> Đăng nhập</a></li>
                    <li><a href="<?php echo BASE_URL; ?>public/register.php"><i class="fa fa-user-plus"></i> Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <div id="header">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="<?php echo BASE_URL; ?>public/index.php" class="logo">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/logo.png" alt="Logo" style="height: 80px !important; width: auto" />
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="header-search">
                        <form action="<?php echo BASE_URL; ?>public/search.php" method="GET">
                            <input class="input" name="keyword" placeholder="Tìm kiếm tại đây" value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>" />
                            <button class="search-btn" type="submit">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-3 clearfix">
                    <div class="header-ctn">
                        <div class="dropdown">
                            <a href="<?php echo BASE_URL; ?>public/cart.php">
                                <i class="fa fa-shopping-cart"></i>
                                <span>Giỏ hàng</span>
                                <div class="qty" id="cart-count"><?= $cart_qty ?? 0 ?></div>
                            </a>
                        </div>
                        <div class="menu-toggle">
                            <a href="#"><i class="fa fa-bars"></i><span>Menu</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<nav id="navigation" class="bg-light border-bottom py-2">
    <div class="container">
        <div id="responsive-nav" class="d-flex flex-wrap align-items-center justify-content-between">
            <ul class="main-nav nav navbar-nav mb-0 d-flex align-items-center">
                <li class="me-3 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>public/index.php">Trang chủ</a>
                </li>
                <li class="me-3 <?= basename($_SERVER['PHP_SELF']) == 'product_list.php' ? 'active' : ''; ?>">
                    <a href="<?php echo BASE_URL; ?>public/product_list.php">Danh Sách Sản Phẩm</a>
                </li>
            </ul>
        </div>
    </div>
</nav>