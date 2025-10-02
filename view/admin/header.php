<?php require_once __DIR__ . '/../../includes/helpers/admin_auth.php'; require_admin_login(); ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Quản Trị</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark border-right" id="sidebar-wrapper">
            <div class="sidebar-heading text-white">PK-Store Admin</div>
            <div class="list-group list-group-flush">
    <a href="dashboard.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-tachometer-alt"></i> Bảng điều khiển
    </a>
    <a href="orders.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-box-open"></i> Quản lý Đơn hàng
    </a>
    <a href="categories.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-list-alt"></i> Quản lý Danh mục
    </a>
    <a href="products.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-tags"></i> Quản lý Sản phẩm
    </a>
    <a href="reviews.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-comments"></i> Quản lý Đánh giá
    </a>
    <a href="users.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-users"></i> Quản lý Khách hàng
    </a>
    <a href="staff.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-user-shield"></i> Quản lý Nhân viên
    </a>
    <a href="vouchers.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-ticket-alt"></i> Quản lý Voucher
    </a>
    <a href="promotions.php" class="list-group-item list-group-item-action bg-dark text-white">
        <i class="fas fa-gift"></i> Quản lý Khuyến mãi
    </a>
</div>
        </div>
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <div class="ms-auto">
                       <span class="navbar-text">
                           Chào, <strong><?php echo htmlspecialchars($_SESSION['staff_username']); ?></strong>
                       </span>
                       <a href="logout.php" class="btn btn-outline-danger ms-2">Đăng xuất</a>
                    </div>
                </div>
            </nav>
            <div class="container-fluid p-4">