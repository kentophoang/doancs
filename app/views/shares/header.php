<?php
// File này nên được gọi từ một file điều phối trung tâm (vd: index.php)
// nơi đã gọi SessionHelper::start()
require_once 'app/helpers/SessionHelper.php';

// --- Cải tiến: Xác định trang công khai hiện tại để làm nổi bật (active) link ---
$request_uri = $_SERVER['REQUEST_URI'];
$current_public_page = 'home'; // Mặc định là trang chủ

if (strpos($request_uri, '/Subject/publicList') !== false) {
    $current_public_page = 'subjects';
} elseif (strpos($request_uri, '/services') !== false) {
    $current_public_page = 'services';
} elseif (strpos($request_uri, '/about') !== false) {
    $current_public_page = 'about';
} elseif (strpos($request_uri, '/blog') !== false) {
    $current_public_page = 'blog';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'Hệ thống Thư viện Thông minh' ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .main-navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .main-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: #2c3e50;
        }
        .main-navbar .nav-link {
            color: #333;
            font-weight: 500;
            transition: color 0.2s ease-in-out;
        }
        .main-navbar .nav-link:hover, .main-navbar .nav-item.active .nav-link {
            color: #0d6efd;
        }
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
            border: none;
        }
        /* CSS cho nút bấm sidebar */
        #sidebarToggle {
            border: none;
        }
        #sidebarToggle:hover {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light main-navbar">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <?php
            // THÊM MỚI: Nút bấm này chỉ hiển thị khi admin đăng nhập
            // và nó sẽ điều khiển thanh bên trong admin_layout.php
            if (SessionHelper::isLoggedIn() && SessionHelper::isAdmin()) :
            ?>
                <button class="btn btn-link text-secondary me-2 p-2" id="sidebarToggle" type="button" aria-label="Toggle sidebar">
                    <i class="fas fa-bars fs-5"></i>
                </button>
            <?php endif; ?>
            
            <a class="navbar-brand" href="/">LIBSMART</a>
        </div>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item <?= ($current_public_page === 'home') ? 'active' : '' ?>">
                    <a class="nav-link" href="/">Trang chủ</a>
                </li>
                <li class="nav-item <?= ($current_public_page === 'subjects') ? 'active' : '' ?>">
                    <a class="nav-link" href="/Subject/publicList">Danh mục sách</a>
                </li>
                <li class="nav-item <?= ($current_public_page === 'services') ? 'active' : '' ?>">
                    <a class="nav-link" href="/services">Dịch vụ</a>
                </li>
                <li class="nav-item <?= ($current_public_page === 'about') ? 'active' : '' ?>">
                    <a class="nav-link" href="/about">Giới thiệu</a>
                </li>
                <li class="nav-item <?= ($current_public_page === 'blog') ? 'active' : '' ?>">
                    <a class="nav-link" href="/blog">Bài viết</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <?php if (SessionHelper::isLoggedIn()): ?>
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            Chào, <?= htmlspecialchars($_SESSION['username']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <?php if (SessionHelper::isAdmin()): ?>
                                <li><a class="dropdown-item" href="/admin/dashboard"><i class="fas fa-cogs fa-fw me-2"></i>Trang quản trị</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item" href="/account/profile"><i class="fas fa-user fa-fw me-2"></i>Hồ sơ của tôi</a></li>
                                <li><a class="dropdown-item" href="/book/myBorrowedBooks"><i class="fas fa-book-reader fa-fw me-2"></i>Sách đã mượn</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/account/logout"><i class="fas fa-sign-out-alt fa-fw me-2"></i>Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="btn btn-outline-primary me-2" href="/account/login">Đăng nhập</a>
                    <a class="btn btn-primary" href="/account/register">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
