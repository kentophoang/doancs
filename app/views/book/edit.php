<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5" style="background: linear-gradient(to right, #d4fcf9, #eaf8fc); padding: 20px; border-radius: 10px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Sửa thông tin sách</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/Book/update" enctype="multipart/form-data" onsubmit="return validateBookForm();">
                        <input type="hidden" name="id" value="<?php echo $book->id; ?>">

                        <div class="form-group mb-3">
                            <label for="name">Tên sách:</label>
                            <input type="text" id="name" name="name" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="author">Tác giả:</label>
                            <input type="text" id="author" name="author" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->author, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="publisher">Nhà xuất bản:</label>
                            <input type="text" id="publisher" name="publisher" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->publisher, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="publication_year">Năm xuất bản:</label>
                            <input type="number" id="publication_year" name="publication_year" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->publication_year, ENT_QUOTES, 'UTF-8'); ?>" required min="1000" max="<?php echo date('Y'); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="ISBN">ISBN:</label>
                            <input type="text" id="ISBN" name="ISBN" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->ISBN, ENT_QUOTES, 'UTF-8'); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Mô tả:</label>
                            <textarea id="description" name="description" class="form-control fs-5" required><?php echo htmlspecialchars($book->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="number_of_copies">Số lượng bản sao:</label>
                            <input type="number" id="number_of_copies" name="number_of_copies" class="form-control fs-5"
                                   value="<?php echo htmlspecialchars($book->number_of_copies, ENT_QUOTES, 'UTF-8'); ?>" required min="1">
                        </div>

                        <div class="form-group mb-3">
                            <label for="subject_id">Chủ đề / Ngành nghề:</label>
                            <select id="subject_id" name="subject_id" class="form-control fs-5" required>
                                <?php
                                    // Hàm đệ quy để hiển thị options có thụt lề
                                    function renderSubjectOptionsEdit($subjectsByParentArray, $parentId, $level = 0, $selectedSubjectId = null) {
                                        if (!isset($subjectsByParentArray[$parentId])) {
                                            return;
                                        }
                                        foreach ($subjectsByParentArray[$parentId] as $subject) {
                                            $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level);
                                            $selected = ($selectedSubjectId == $subject->id) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($subject->id) . '" ' . $selected . '>' . $indent . htmlspecialchars($subject->name) . '</option>';
                                            renderSubjectOptionsEdit($subjectsByParentArray, $subject->id, $level + 1, $selectedSubjectId);
                                        }
                                    }

                                    // Render các chủ đề cấp cao nhất (parent_id là NULL hoặc 0)
                                    // $subjectsByParent và $book->subject_id phải được truyền từ controller
                                    renderSubjectOptionsEdit($subjectsByParent, 0, 0, $book->subject_id); // Đối với parent_id = 0
                                    renderSubjectOptionsEdit($subjectsByParent, null, 0, $book->subject_id); // Đối với parent_id = NULL
                                ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Hình ảnh/Bìa sách:</label>
                            <input type="file" id="image" name="image" class="form-control fs-5">
                            <input type="hidden" name="existing_image" value="<?php echo $book->image; ?>">
                            <?php if ($book->image): ?>
                                <div class="mt-2">
                                    <img src="/<?php echo $book->image; ?>" alt="Book Image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fs-5">Lưu thay đổi</button>
                    </form>

                    <a href="/Book/list" class="btn btn-secondary w-100 mt-3 fs-5">Quay lại danh sách sách</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
function validateBookForm() { // Hàm validate giống như add.php
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