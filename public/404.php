<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .error-message {
            max-width: 600px;
        }

        .error-message h1 {
            font-size: 10rem;
            color: #dc3545;
        }

        .error-message p {
            font-size: 1.25rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="error-message">
            <h1>404</h1>
            <p>Xin lỗi, không thể tìm thấy trang này.</p>
            <a href="../public/login.php" class="btn btn-primary mt-3">Quay lại trang Đăng nhập</a>
        </div>
    </div>
</body>

</html>