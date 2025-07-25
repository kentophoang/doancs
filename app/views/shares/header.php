<?php
// Tệp này chỉ chứa phần <head> và navbar chính của ứng dụng.
// Nó được include MỘT LẦN ở đầu index.php.
// KHÔNG BAO GỒM biến $main_content.
// KHÔNG include các tệp header/footer khác.
// Các require_once và session_start() đã được chuyển sang index.php để tránh output sớm.
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Thư viện Thông minh</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        /* CSS tuỳ chỉnh để khớp với bố cục trong ảnh */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 0.5rem 1.5rem;
            border-bottom: 1px solid #e3e6f0;
            z-index: 1030;
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
        .navbar-brand {
            color: #34495e;
            font-weight: bold;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        .navbar-brand:hover {
            color: #2c3e50;
        }
        .nav-item .nav-link {
            color: #333;
            font-weight: 500;
            padding: 0.5rem 1rem;
        }
        .nav-item .nav-link:hover {
            color: #007bff;
        }
        .btn-primary-custom {
            background-color: #007bff;
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-primary-custom:hover {
            background-color: #0056b3;
        }
        .btn-secondary-custom {
            background-color: #6c757d;
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-secondary-custom:hover {
            background-color: #5a6268;
        }
        .login-buttons {
            display: flex;
            align-items: center;
        }
        .login-buttons a {
            margin-left: 10px;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <span class="logo-text">LIBSMART</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/Book">Danh mục sách</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/services">Dịch vụ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">Giới thiệu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/blog">Bài viết</a>
                    </li>
                </ul>

                <div class="login-buttons">
                    <?php
                    // Đảm bảo SessionHelper được include
                    require_once('app/helpers/SessionHelper.php');
                    ?>

                    <?php if (\SessionHelper::isLoggedIn()): ?>
                        <?php if (\SessionHelper::isAdmin()): ?>
                            <a href="/admin/dashboard" class="nav-link">Quản trị viên</a>
                            <a class="btn btn-secondary-custom" href="/account/logout">Đăng xuất</a>
                        <?php else: ?>
                            <a class="nav-link" href="/account/profile">Chào, <?= htmlspecialchars($_SESSION['username']) ?></a>
                            <a class="btn btn-secondary-custom" href="/account/logout">Đăng xuất</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a class="btn btn-secondary-custom" href="/account/login">Đăng nhập</a>
                        <a class="btn btn-primary-custom" href="/account/register">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>