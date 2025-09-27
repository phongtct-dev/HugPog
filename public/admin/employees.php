<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang có Sidebar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/css/perfect-scrollbar.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../asset/bootstrap/css/app.css">
    <link rel="stylesheet" href="../asset/bootstrap/css/style.css">

</head>

<body>
    <div id="app">
        <?php
        session_start();
        // Kiểm tra session 'username' đã tồn tại hay chưa
        if (!isset($_SESSION['username'])) {
            // Nếu không tồn tại, chuyển hướng đến trang 404
            header("Location: ../404.php");
            exit(); // Dừng tất cả các mã PHP tiếp theo
        }
        include __DIR__ . '/../../templates/sidebar.html';
        include __DIR__ . '/../../templates/admin/employee_list.html';
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/perfect-scrollbar@1.5.5/dist/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Bật perfect scrollbar cho sidebar
        new PerfectScrollbar('#sidebar');
    </script>
    <script src="../asset/bootstrap/js/main.js"></script>
    <script src="../asset/bootstrap/js/api_admin.js"></script>

</body>

</html>