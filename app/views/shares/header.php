<?php
require_once('app/models/CategoryModel.php');
require_once('app/config/database.php');

$db = (new Database())->getConnection();
$categoryModel = new CategoryModel($db);
$categories = $categoryModel->getCategories();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        
        /* Navbar */
        .navbar {
            background: linear-gradient(to right, #00bcd4, #00796b);
            color: white;
            border-radius: 10px;
            padding: 10px 0;
        }
        .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
            transition: color 0.3s ease;
            white-space: nowrap;
            padding: 10px 15px;
        }
        .navbar-nav .nav-link:hover {
            color: #ff9800 !important;
        }
        .form-inline {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }
        .form-control {
            border-radius: 30px;
            padding: 10px 20px;
            width: 250px;
            background: #ffffff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-control:focus {
            border-color: #00bcd4;
            box-shadow: 0px 0px 15px rgba(0, 188, 212, 0.4);
        }
        .btn-outline-success {
            border-radius: 25px;
            padding: 10px 20px;
            background: linear-gradient(to right, #00bcd4, #00796b);
            color: white;
            margin-left: -5px;
        }
        .btn-outline-success:hover {
            background: linear-gradient(to left, #00bcd4, #00796b);
        }
        .navbar-toggler {
            border-color: #ffffff;
        }
        .navbar-toggler-icon {
            background-color: #ffffff;
        }
    </style>
</head>
<body>



    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="/Product/">Danh sách sản phẩm</a>
                    </li>
                    

                    <!-- Dropdown Quản lý danh mục -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="categoryDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Danh Mục
                        </a>
                        <div class="dropdown-menu" aria-labelledby="categoryDropdown">
                            <?php if (empty($categories)) : ?>
                                <p class="dropdown-item text-muted">Chưa có danh mục nào</p>
                            <?php else : ?>
                                <?php foreach ($categories as $category) : ?>
                                    <a class="dropdown-item" href="/Product?category_id=<?= htmlspecialchars($category->id) ?>">
                                        <?= htmlspecialchars($category->name) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="dropdown-divider"></div>

                            <!-- Chỉ admin mới thấy "Quản Lý Danh Mục" -->
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                                <a class="dropdown-item" href="/Category/">Quản Lý Danh Mục</a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <!-- Chỉ admin mới thấy -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Product/add">Thêm sản phẩm</a>
                        </li>
                    <?php endif; ?>

                    <!-- Chỉ user hoặc admin mới thấy "Giỏ hàng" -->
                    <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'user')) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Product/cart">Giỏ hàng</a>
                        </li>
                      

                    <li class="nav-item">
                        <a class="nav-link" href="/Product/checkout">Thanh Toán</a>
                    </li>
                    <?php endif; ?>  
                    <!-- Thanh tìm kiếm -->
                    <li class="nav-item">
                        <form class="form-inline" action="/Product/search" method="get">
                            <input class="form-control mr-sm-2" type="search" placeholder="Tìm sản phẩm" aria-label="Search" name="search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm</button>
                        </form>
                    </li>

                    <!-- Hiển thị username nếu đã đăng nhập -->
                    <li class="nav-item">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link"><?= htmlspecialchars($_SESSION['username']) ?></a>
                        <?php else : ?>
                            <a class="nav-link" href="/account/login">Đăng nhập</a>
                        <?php endif; ?>
                    </li>

                    <!-- Nút logout chỉ hiển thị khi đã đăng nhập -->
                    <li class="nav-item">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link" href="/account/logout">Đăng xuất</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Nội dung trang -->
    <div class="container mt-4">
        <!-- Nội dung sản phẩm sẽ được hiển thị ở đây -->
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
