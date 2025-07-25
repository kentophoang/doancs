<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trạng thái Xác thực - LIBSMART</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body { 
            background-color: #f0f2f5; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem;
        }
        .verification-container { 
            max-width: 550px; 
            width: 100%;
        }
        .card {
            border: none;
            border-top: 5px solid;
            border-radius: .5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1);
        }
        .icon { 
            font-size: 4rem; 
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container verification-container">
        <div class="card text-center" style="border-top-color: <?= htmlspecialchars($icon_color ?? '#6c757d') ?>;">
            <div class="card-body p-4 p-md-5">
                <i class="icon <?= htmlspecialchars($icon_class ?? 'fas fa-info-circle') ?> mb-3" style="color: <?= htmlspecialchars($icon_color ?? '#6c757d') ?>;"></i>
                <h1 class="card-title"><?= htmlspecialchars($header_text ?? 'Thông báo') ?></h1>
                <p class="card-text fs-5 text-muted"><?= htmlspecialchars($message ?? 'Đã có lỗi xảy ra.') ?></p>
                <a href="/account/login" class="btn btn-primary mt-4 px-5">Về trang Đăng nhập</a>
            </div>
        </div>
    </div>
</body>
</html>