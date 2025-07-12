<?php include 'app/views/shares/header.php'; ?>

<div class="container py-1" style="margin-top: -30px;">


    <?php $categoryParam = isset($_GET['category_id']) ? '&category_id=' . $_GET['category_id'] : ''; ?>

    <?php 
    $categoryName = "Danh sách sản phẩm";
    if (isset($_GET['category_id'])) {
        $category = $this->categoryModel->getCategoryById($_GET['category_id']);
        if ($category) {
            $categoryName = "Danh sách sản phẩm: " . htmlspecialchars($category->name);
        }
    }
?>

<h1 class="text-center mb-0.1"><?= $categoryName ?></h1>


    <!-- Sắp xếp theo -->
    <div class="row mb-2">
        <div class="col-md-5.5 d-flex align-items-center">
            <h6 class="text-custom mr-2" style="font-weight: bold;">Sắp Xếp Theo:</h6>
            <div class="sort-buttons d-flex align-items-center">
                <a href="?sort=high<?= $categoryParam ?>" class="btn btn-light btn-sm mr-20" style="border: 1px solid #ccc; border-radius: 5px;">Giá Cao</a>
                <a href="?sort=low<?= $categoryParam ?>" class="btn btn-light btn-sm mr-1" style="border: 1px solid #ccc; border-radius: 5px;">Giá Thấp</a>
            </div>
        </div>

    <div class="col-md-6 text-right">
        <h6 class="text-custom mb-1 " style="font-weight: bold;"> </h6>
        <form method="GET" action="" class="d-flex align-items-center justify-content-end">
            <?php if (isset($_GET['category_id'])): ?>
                <input type="hidden" name="category_id" value="<?= htmlspecialchars($_GET['category_id']) ?>">
            <?php endif; ?>
            <select name="price_range" id="price_range" class="form-select form-select-sm w-auto mr-1" style="border: 1px solid #000; border-radius: 5px;">
                <option value="">Tất cả</option>
                <option value="0-2000000" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '0-2000000') echo 'selected'; ?>>Dưới 2 triệu</option>
                <option value="2000000-4000000" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '2000000-4000000') echo 'selected'; ?>>Từ 2 - 4 triệu</option>
                <option value="4000000-7000000" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '4000000-7000000') echo 'selected'; ?>>Từ 4 - 7 triệu</option>
                <option value="7000000-13000000" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '7000000-13000000') echo 'selected'; ?>>Từ 7 - 13 triệu</option>
                <option value="13000000-20000000" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '13000000-20000000') echo 'selected'; ?>>Từ 13 - 20 triệu</option>
                <option value="20000000-999999999" <?php if (isset($_GET['price_range']) && $_GET['price_range'] === '20000000-999999999') echo 'selected'; ?>>Trên 20 triệu</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 5px;">Lọc</button>
        </form>
    </div>
</div>





    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-md-3 mb-4">
                    <div class="card product-card h-100">
                        <?php if ($product->image): ?>
                            <img src="/<?php echo $product->image; ?>" class="card-img-top" alt="Product Image">
                        <?php else: ?>
                            <img src="/uploads/default-image.jpg" class="card-img-top" alt="Product Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/Product/show/<?php echo $product->id; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <p class="card-text"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text"><strong>Giá:</strong> <?php echo number_format($product->price, 0, ',', '.'); ?> đ
                            <p class="card-text"><strong>Danh mục:</strong> <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="/Product/show/<?php echo $product->id; ?>" class="btn btn-info">Xem</a>
                                <a href="/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning">Sửa</a>
                                <a href="/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'user')): ?>
                                <a href="/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary">Thêm vào giỏ hàng</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Không có sản phẩm trong danh mục này.</p>
        <?php endif; ?>
    </div>
</div>

<style>


    body {
        background: linear-gradient(to right, #d4fcf7, #a0c4ff);
        font-family: 'Arial', sans-serif;
    }
    .product-card {
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }
    .product-card:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    .card-footer {
        background: #f8f9fa;
    }

    /* Điều chỉnh giao diện cho mobile */
    @media (max-width: 768px) {
        .d-flex.align-items-center {
            flex-direction: column;
        }
        .d-flex.align-items-center select,
        .d-flex.align-items-center button {
            width: 100%;
            margin-top: 5px;
        }
    }

    
    .container.py-4 {
        background-color: transparent !important;
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .row.mb-3 {
        display: flex;
        align-items: flex-start; /* Căn chỉnh các cột từ trên xuống */
        gap: 20px;
        margin-bottom: 20px;
        background-color: transparent !important;
    }

    .col-md-6 {
        padding: 0;
        background-color: transparent !important;
        display: flex;
        align-items: center;
    }




    .text-custom {
        color: #333; /* Màu đen cho chữ */
    }

    .sort-buttons {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-light {
        background-color: #fff; /* Nền trắng cho nút */
        color: #333;
        border: 1px solid #ccc; /* Viền xám nhạt */
    }

    .btn-light:hover {
        background-color: #f8f9fa;
        color: #333;
    }

    .text-right {
        text-align: right !important;
    }

    .filter-price-container {
        display: flex;
        flex-direction: column; /* Sắp xếp tiêu đề và form theo chiều dọc */
        align-items: flex-end; /* Căn phải nội dung */
    }

    .filter-price-container form {
        display: flex;
        align-items: center;
        gap: 5px; /* Thêm lại khoảng cách nhỏ giữa dropdown và nút Lọc nếu cần */
    }

    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        height: auto; /* Đảm bảo chiều cao tự động */
    }

    .btn-primary {
        font-size: 0.875rem;
    }

    .mr-1 {
        margin-right: 0.25rem !important; /* Giảm khoảng cách bên phải dropdown */
    }
</style>
<?php include 'app/views/shares/footer.php'; ?>
