<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật chủ đề / ngành nghề</title>
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
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-4">
    <h2 class="text-center">Cập nhật chủ đề / ngành nghề</h2>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <form action="/Subject/update/<?= $subject->id ?>" method="POST">
        <div class="form-group">
            <label for="name">Tên chủ đề / ngành nghề</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($subject->name) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea class="form-control" id="description" name="description" required><?= isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($subject->description) ?></textarea>
        </div>
        <div class="form-group">
            <label for="parent_id">Chủ đề cha (tùy chọn)</label>
            <select class="form-control" id="parent_id" name="parent_id">
                <option value="">— Chọn chủ đề cha —</option>
                <?php 
                // Function to render hierarchical subject options
                function renderSubjectOptionsEdit($subjectsByParentArray, $parentId, $level = 0, $selectedSubjectId = null, $currentSubjectId = null) {
                    if (!isset($subjectsByParentArray[$parentId])) {
                        return;
                    }
                    foreach ($subjectsByParentArray[$parentId] as $s) {
                        if ($s->id == $currentSubjectId) continue; // Prevent a subject from being its own parent

                        $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);
                        $selected = ($selectedSubjectId == $s->id) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($s->id) . '" ' . $selected . '>' . $indent . htmlspecialchars($s->name) . '</option>';
                        renderSubjectOptionsEdit($subjectsByParentArray, $s->id, $level + 1, $selectedSubjectId, $currentSubjectId);
                    }
                }

                // Render options for parent subjects
                // $subjectsByParent and $subject->parent_id must be passed from controller
                renderSubjectOptionsEdit($subjectsByParent, 0, 0, (isset($_POST['parent_id']) ? $_POST['parent_id'] : $subject->parent_id), $subject->id);
                renderSubjectOptionsEdit($subjectsByParent, null, 0, (isset($_POST['parent_id']) ? $_POST['parent_id'] : $subject->parent_id), $subject->id);
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật chủ đề</button>
    </form>

    <a href="/Subject/index" class="btn btn-secondary mt-3">Quay lại danh sách</a>
    <a href="/Book/index" class="btn btn-primary mt-3">Quay lại danh sách sách</a>       

</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>