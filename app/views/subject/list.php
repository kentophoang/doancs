<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách chủ đề</title>
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
        .btn-success, .btn-warning, .btn-danger {
            margin-right: 10px; /* Khoảng cách giữa các nút */
        }
        .btn-success {
            background-color: #00796b;
            border-color: #00796b;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }
        .btn:hover {
            opacity: 0.8;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Danh sách chủ đề</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message); ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <a href="/Subject/create" class="btn btn-success mb-3">Thêm chủ đề mới</a>

    <table class="table">
        <thead>
            <tr>
                <th>Tên chủ đề</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($subjects as $subject) : // Changed $categories to $subjects ?>
                <tr>
                    <td><?= htmlspecialchars($subject->name) ?></td>
                    <td>
                        <a href="/Subject/edit/<?= $subject->id ?>" class="btn btn-warning">Sửa</a>
                        <a href="/Subject/delete/<?= $subject->id ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa chủ đề này?');">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>