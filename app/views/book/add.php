

<div class="container mt-4">
    <h2 class="text-center mb-4">➕ Thêm sách mới</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg p-4 form-container">
        <form method="POST" action="/Book/save" enctype="multipart/form-data" onsubmit="return validateBookForm();">
            <div class="mb-3">
                <label for="name" class="form-label">📌 Tên sách:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sách" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="author" class="form-label">✍️ Tác giả:</label>
                <input type="text" id="author" name="author" class="form-control" placeholder="Nhập tên tác giả" required value="<?= htmlspecialchars($_POST['author'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="publisher" class="form-label">🏢 Nhà xuất bản:</label>
                <input type="text" id="publisher" name="publisher" class="form-control" placeholder="Nhập tên nhà xuất bản" value="<?= htmlspecialchars($_POST['publisher'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="publication_year" class="form-label">🗓️ Năm xuất bản:</label>
                <input type="number" id="publication_year" name="publication_year" class="form-control" placeholder="Nhập năm xuất bản" required min="1000" max="<?php echo date('Y'); ?>" value="<?= htmlspecialchars($_POST['publication_year'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="ISBN" class="form-label">🆔 ISBN:</label>
                <input type="text" id="ISBN" name="ISBN" class="form-control" placeholder="Nhập ISBN của sách" value="<?= htmlspecialchars($_POST['ISBN'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">📝 Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả sách" required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
            </div>

            <div class="mb-3">
                <label for="number_of_copies" class="form-label">📚 Số lượng bản sao:</label>
                <input type="number" id="number_of_copies" name="number_of_copies" class="form-control" placeholder="Tổng số lượng bản sao" required min="1" value="<?= htmlspecialchars($_POST['number_of_copies'] ?? '') ?>">
            </div>

            <div class="mb-3">
                <label for="subject_id" class="form-label">📂 Chủ đề / Ngành nghề:</label>
                <select id="subject_id" name="subject_id" class="form-select" required>
                    <option value="" disabled selected>Chọn chủ đề/ngành nghề</option>
                    <?php
                    // Hàm đệ quy để hiển thị options có thụt lề
                    function renderSubjectOptions($subjectsByParentArray, $parentId, $level = 0, $selectedSubjectId = null) {
                        if (!isset($subjectsByParentArray[$parentId])) {
                            return;
                        }
                        foreach ($subjectsByParentArray[$parentId] as $subject) {
                            $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level); // Thụt lề bằng khoảng trắng
                            $selected = ($selectedSubjectId == $subject->id) ? 'selected' : '';
                            echo '<option value="' . htmlspecialchars($subject->id) . '" ' . $selected . '>' . $indent . htmlspecialchars($subject->name) . '</option>';
                            renderSubjectOptions($subjectsByParentArray, $subject->id, $level + 1, $selectedSubjectId);
                        }
                    }

                    // Render các chủ đề cấp cao nhất (parent_id là NULL hoặc 0)
                    renderSubjectOptions($subjectsByParent, 0, 0, ($_POST['subject_id'] ?? null)); // Đối với parent_id = 0
                    renderSubjectOptions($subjectsByParent, null, 0, ($_POST['subject_id'] ?? null)); // Đối với parent_id = NULL
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">🖼️ Hình ảnh/Bìa sách:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-50">➕ Thêm sách</button>
                <a href="/Book/list" class="btn btn-secondary w-50 ms-2">⬅️ Quay lại</a>
            </div>
            <div class="mb-3">
                <label for="location" class="form-label">Vị trí trong thư viện</label>
                <input type="text" class="form-control" id="location" name="location" placeholder="Ví dụ: Kệ A3, Tầng 2" value="<?= htmlspecialchars($book->location ?? '') ?>">
            </div>
        </form>
    </div>
</div>



<style>
    body {
        background: linear-gradient(to bottom right, #d4faff, #b3ecff);
    }
    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease-in-out;
    }
    .form-container:hover {
        transform: scale(1.02);
    }
    .form-control, .form-select {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
    }
    .btn-primary {
        background: #007bff;
        border: none;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-primary:hover {
        background: #0056b3;
    }
    .btn-secondary {
        background: #6c757d;
        border: none;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-secondary:hover {
        background: #545b62;
    }
    @media (max-width: 768px) {
        .form-container {
            width: 90%;
        }
        .btn {
            font-size: 14px;
        }
    }
</style>

<script>
function validateBookForm() {
    let name = document.getElementById("name").value.trim();
    let author = document.getElementById("author").value.trim();
    let publication_year = document.getElementById("publication_year").value.trim();
    let description = document.getElementById("description").value.trim();
    let number_of_copies = document.getElementById("number_of_copies").value.trim();
    let subject = document.getElementById("subject_id").value;

    if (name === "" || author === "" || publication_year === "" || description === "" || number_of_copies === "" || subject === "") {
        alert("Vui lòng điền đầy đủ thông tin!");
        return false;
    }

    if (publication_year <= 0 || publication_year > new Date().getFullYear()) {
        alert("Năm xuất bản không hợp lệ!");
        return false;
    }

    if (number_of_copies <= 0) {
        alert("Số lượng bản sao phải lớn hơn 0!");
        return false;
    }

    return true;
}
</script>