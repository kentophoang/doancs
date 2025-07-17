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
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5; /* Admin dashboard background */
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background-color: #ffffff; /* White background for admin header */
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important; /* Admin card shadow */
            padding: 0.5rem 1.5rem; /* Adjusted padding */
            border-bottom: 1px solid #e3e6f0; /* Light border at bottom */
            z-index: 1030; /* Higher than sidebar */
            position: sticky;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
        }
        .navbar-brand {
            color: #34495e !important; /* Dark text for brand */
            font-weight: bold;
            font-size: 1.3rem; /* Slightly smaller for admin header */
            transition: all 0.3s ease;
        }
        .navbar-brand:hover {
            color: #2c3e50 !important;
        }
        .form-inline .form-control {
            border-radius: 5px; /* Less rounded than public */
            border: 1px solid #ced4da;
            box-shadow: none;
            padding: 0.375rem 0.75rem; /* Standard form control padding */
            width: 250px; /* Specific width for screenshot match */
        }
        .form-inline .btn-search {
            background-color: #4e73df; /* Blue search button matching admin theme */
            border-color: #4e73df;
            color: white;
            padding: 0.375rem 0.75rem;
            border-radius: 5px;
            margin-left: 5px;
            transition: background-color 0.3s ease;
        }
        .form-inline .btn-search:hover {
            background-color: #2e59d9;
            border-color: #2e59d9;
        }
        .admin-profile-header {
            color: #34495e; /* Dark text */
            font-weight: bold;
            display: flex;
            align-items: center;
            margin-left: 15px;
        }
        .admin-profile-header i {
            margin-right: 8px;
            color: #6c757d; /* Lighter icon color */
        }
        .btn-logout-header {
            background-color: #e74a3b; /* Red logout button */
            border-color: #e74a3b;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            margin-left: 10px;
        }
        .btn-logout-header:hover {
            background-color: #c0392b;
            border-color: #c0392b;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">📚 LIBSMART</a>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto align-items-center">
                    <li class="nav-item d-flex align-items-center">
                        <form class="form-inline my-2 my-lg-0 mr-3" action="/Book" method="get">
                            <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm sách, thành viên, hoặc ISBN..." aria-label="Search" name="search">
                            <button class="btn btn-search my-2 my-sm-0" type="submit">Tìm</button>
                        </form>
                    </li>
                    <?php if (isset($_SESSION['username']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li class="nav-item admin-profile-header">
                            <i class="fas fa-user-circle"></i> Quản trị viên, <?= htmlspecialchars($_SESSION['username']) ?>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-logout-header" href="/account/logout">Đăng xuất</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                            <a class="nav-link btn btn-header btn-login mr-2" href="/account/login">Đăng nhập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link btn btn-header btn-register" href="/account/register">Đăng ký</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>