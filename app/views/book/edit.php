<?php
// Lấy dữ liệu sách và chủ đề đã được truyền từ controller
$book = $data['book'] ?? null;
$subjectsByParent = $data['subjectsByParent'] ?? [];
$allSubjects = $data['allSubjects'] ?? [];

// Lấy lỗi và dữ liệu cũ từ session nếu có
$errors = $_SESSION['form_errors'] ?? [];
$old_data = $_SESSION['POST_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['POST_data']);

// Hàm đệ quy để hiển thị các tùy chọn chủ đề
function renderSubjectOptions($subjects, $parentId = 0, $level = 0, $selectedId = null) {
    if (!isset($subjects[$parentId])) return;
    foreach ($subjects[$parentId] as $subject) {
        $indent = str_repeat('-- ', $level);
        $isSelected = ($selectedId == $subject->id) ? 'selected' : '';
        echo "<option value='{$subject->id}' {$isSelected}>{$indent}" . htmlspecialchars($subject->name) . "</option>";
        renderSubjectOptions($subjects, $subject->id, $level + 1, $selectedId);
    }
}
?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h2 class="h4 mb-0">Chỉnh sửa sách: <?= htmlspecialchars($book->name) ?></h2>
                </div>
                <div class="card-body">
                    <!-- Hiển thị lỗi nếu có -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="/book/update" method="POST" enctype="multipart/form-data">
                        <!-- Thêm input ẩn để gửi ID của sách -->
                        <input type="hidden" name="id" value="<?= htmlspecialchars($book->id) ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Tên sách</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($old_data['name'] ?? $book->name) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="author" class="form-label">Tác giả</label>
                            <input type="text" class="form-control" id="author" name="author" value="<?= htmlspecialchars($old_data['author'] ?? $book->author) ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control" id="description" name="description" rows="5"><?= htmlspecialchars($old_data['description'] ?? $book->description) ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="subject_id" class="form-label">Chủ đề</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">-- Chọn chủ đề --</option>
                                    <?php renderSubjectOptions($subjectsByParent, 0, 0, $old_data['subject_id'] ?? $book->subject_id); ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="publication_year" class="form-label">Năm xuất bản</label>
                                <input type="number" class="form-control" id="publication_year" name="publication_year" value="<?= htmlspecialchars($old_data['publication_year'] ?? $book->publication_year) ?>" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="ISBN" class="form-label">Mã ISBN</label>
                                <input type="text" class="form-control" id="ISBN" name="ISBN" value="<?= htmlspecialchars($old_data['ISBN'] ?? $book->ISBN) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="number_of_copies" class="form-label">Tổng số bản sao</label>
                                <input type="number" class="form-control" id="number_of_copies" name="number_of_copies" value="<?= htmlspecialchars($old_data['number_of_copies'] ?? $book->number_of_copies) ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Ảnh bìa</label>
                            <div class="mb-2">
                                <p class="mb-1">Ảnh bìa hiện tại:</p>
                                <img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" alt="Ảnh bìa hiện tại" style="max-width: 100px; height: auto; border-radius: 4px;">
                            </div>
                            <input type="file" class="form-control" id="image" name="image">
                            <small class="form-text text-muted">Để trống nếu bạn không muốn thay đổi ảnh bìa.</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="/Book/list" class="btn btn-secondary me-2">Hủy bỏ</a>
                            <button type="submit" class="btn btn-primary">Cập nhật sách</button>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Vị trí trong thư viện</label>
                            <input type="text" class="form-control" id="location" name="location" placeholder="Ví dụ: Kệ A3, Tầng 2" value="<?= htmlspecialchars($book->location ?? '') ?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
