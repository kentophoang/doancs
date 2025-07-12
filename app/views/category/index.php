<!-- app/views/category/index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách danh mục</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d3fcf7; /* Phông nền xanh nhạt */
        }
        .container {
            background-color: #ffffff; /* Màu nền của form */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #00796b; /* Màu chữ tiêu đề */
        }
        .btn-success {
            background-color: #00796b; /* Nút thêm danh mục */
            border-color: #00796b;
        }
        .btn-info {
            background-color: #0288d1; /* Nút xem danh mục */
            border-color: #0288d1;
        }
        .btn-warning {
            background-color: #ff9800; /* Nút sửa danh mục */
            border-color: #ff9800;
        }
        .btn-danger {
            background-color: #f44336; /* Nút xóa danh mục */
            border-color: #f44336;
        }
        .btn-primary {
            background-color: #0288d1; /* Nút quay lại danh sách sản phẩm */
            border-color: #0288d1;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .table th, .table td {
            text-align: center;
        }
        .table {
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Danh sách danh mục</h2>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message); ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <!-- Nút Thêm danh mục mới -->
    <a href="/Category/create" class="btn btn-success mb-3">Thêm danh mục mới</a>

    <!-- Bảng hiển thị danh mục -->
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Tên danh mục</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= htmlspecialchars($category->name) ?></td>
                    <td><?= htmlspecialchars($category->description) ?></td>
                    <td>
                        <a href="/Category/view/<?= $category->id ?>" class="btn btn-info">Xem</a>
                        <a href="/Category/edit/<?= $category->id ?>" class="btn btn-warning">Sửa</a>
                        <a href="/Category/delete/<?= $category->id ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Nút quay lại danh sách sản phẩm -->
    <a href="/Product/index" class="btn btn-primary mt-3">Quay lại danh sách sản phẩm</a>       
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
