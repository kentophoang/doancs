<!-- app/views/category/view.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin danh mục</title>
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
        .card {
            border-radius: 10px;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }
        .btn-secondary {
            background-color: #0288d1;
            border-color: #0288d1;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .card-header {
            background-color: #00796b;
            color: white;
            border-radius: 10px 10px 0 0;
        }
        .card-footer {
            background-color: #f1f1f1;
            border-radius: 0 0 10px 10px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Thông tin danh mục</h2>

    <!-- Card hiển thị thông tin danh mục -->
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?= htmlspecialchars($category->name) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Mô tả:</strong></p>
            <p><?= htmlspecialchars($category->description) ?></p>
        </div>
        <div class="card-footer text-center">
            <a href="/Category/edit/<?= $category->id ?>" class="btn btn-warning">Sửa danh mục</a>
            <a href="/Category/delete/<?= $category->id ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">Xóa danh mục</a>
            <a href="/Category/index" class="btn btn-secondary">Quay lại danh sách</a>
        
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
