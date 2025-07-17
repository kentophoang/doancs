<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách Chủ đề / Ngành nghề</title>
    <link href="[https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css](https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css)" rel="stylesheet">
    <style>
        body {
            background-color: #d3fcf7;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #00796b;
        }
        .btn-success {
            background-color: #00796b;
            border-color: #00796b;
        }
        .btn-info {
            background-color: #0288d1;
            border-color: #0288d1;
        }
        .btn-warning {
            background-color: #ff9800;
            border-color: #ff9800;
        }
        .btn-danger {
            background-color: #f44336;
            border-color: #f44336;
        }
        .btn-primary {
            background-color: #0288d1;
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
        .subject-indent {
            padding-left: 20px; /* Adjust as needed */
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Danh sách Chủ đề / Ngành nghề</h2>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message); ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <a href="/Subject/create" class="btn btn-success mb-3">Thêm chủ đề/ngành nghề mới</a>

    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th>Tên chủ đề/ngành nghề</th>
                <th>Mô tả</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Hàm đệ quy để hiển thị các hàng trong bảng
            function displaySubjectTableRows($subjectsByParentArray, $parentId, $level = 0) {
                if (!isset($subjectsByParentArray[$parentId])) {
                    return;
                }
                foreach ($subjectsByParentArray[$parentId] as $subject) {
                    $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level); // Thụt lề bằng khoảng trắng
                    echo '<tr>';
                    echo '<td style="text-align: left;"><span class="subject-indent">' . $indent . htmlspecialchars($subject->name) . '</span></td>';
                    echo '<td>' . htmlspecialchars($subject->description) . '</td>';
                    echo '<td>';
                    echo '<a href="/Subject/view/' . $subject->id . '" class="btn btn-info">Xem</a>';
                    echo '<a href="/Subject/edit/' . $subject->id . '" class="btn btn-warning">Sửa</a>';
                    echo '<a href="/Subject/delete/' . $subject->id . '" class="btn btn-danger" onclick="return confirm(\'Bạn có chắc chắn muốn xóa chủ đề này?\');">Xóa</a>';
                    echo '</td>';
                    echo '</tr>';
                    displaySubjectTableRows($subjectsByParentArray, $subject->id, $level + 1); // Gọi đệ quy cho chủ đề con
                }
            }

            // Hiển thị các chủ đề cấp cao nhất (parent_id là NULL hoặc 0)
            displaySubjectTableRows($subjectsByParent, 0); // Đối với parent_id = 0
            displaySubjectTableRows($subjectsByParent, null); // Đối với parent_id = NULL
            
            if (empty($subjectsByParent[0]) && empty($subjectsByParent[null])) :
            ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">Chưa có chủ đề/ngành nghề nào.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="/Book/index" class="btn btn-primary mt-3">Quay lại danh sách sách</a>       
</div>

<script src="[https://code.jquery.com/jquery-3.5.1.min.js](https://code.jquery.com/jquery-3.5.1.min.js)"></script>
<script src="[https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js](https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js)"></script>
<script src="[https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js](https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js)"></script>

</body>
</html>