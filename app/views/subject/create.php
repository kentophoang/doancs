<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm chủ đề / ngành nghề mới</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .btn-primary {
            background-color: #007bff; /* Changed to general primary color for consistency */
            border-color: #007bff; /* Changed to general primary color for consistency */
        }
        .btn-secondary {
            background-color: #6c757d; /* Changed to general secondary color for consistency */
            border-color: #6c757d; /* Changed to general secondary color for consistency */
        }
        .btn-primary:hover, .btn-secondary:hover {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Thêm chủ đề / ngành nghề mới</h2>

    <form action="/Subject/store" method="POST">
        <div class="form-group">
            <label for="name">Tên chủ đề / ngành nghề</label>
            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label for="parent_id">Chủ đề cha (tùy chọn)</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">— Chọn chủ đề cha —</option>
                <?php foreach ($parentSubjects as $pSubject): // $parentSubjects được truyền từ SubjectController ?>
                    <option value="<?= htmlspecialchars($pSubject->id) ?>"
                        <?= (isset($_POST['parent_id']) && $_POST['parent_id'] == $pSubject->id) ? 'selected' : '' ?>
                    >
                        <?= htmlspecialchars($pSubject->name) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thêm chủ đề</button>
    </form>

    <a href="/Subject/index" class="btn btn-secondary mt-3">Quay lại danh sách</a>
    <a href="/Book/index" class="btn btn-primary mt-3">Quay lại danh sách sách</a>       

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>