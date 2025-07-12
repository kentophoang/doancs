<!-- app/views/category/create.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm danh mục mới</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #d3fcf7; /* Màu nền xanh nhạt */
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
        .btn-primary {
            background-color: #00796b; /* Nút với màu xanh */
            border-color: #00796b;
        }
        .btn-secondary {
            background-color: #0288d1; /* Nút quay lại màu xanh dương */
            border-color: #0288d1;
        }
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Thêm danh mục mới</h2>

    <!-- Form thêm danh mục mới -->
    <form action="/Category/store" method="POST">
        <div class="form-group">
            <label for="name">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Thêm danh mục</button>
    </form>

    <a href="/Category/index" class="btn btn-secondary mt-3">Quay lại danh sách</a>
    <a href="/Product/index" class="btn btn-primary mt-3">Quay lại danh sách sản phẩm</a>       

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
