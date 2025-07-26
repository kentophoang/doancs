<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        .error-container {
            max-width: 500px;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #6c757d;
        }
        .error-heading {
            font-size: 1.75rem;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container text-center error-container">
        <div class="error-code">404</div>
        <p class="error-heading text-secondary">Oops! Không tìm thấy trang.</p>
        <p class="text-muted">
            Rất tiếc, trang bạn đang tìm kiếm không tồn tại, có thể đã bị di chuyển hoặc xóa.
        </p>
        <a href="/" class="btn btn-primary mt-3">Quay về Trang chủ</a>
    </div>
</body>
</html>