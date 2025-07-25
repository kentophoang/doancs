<?php
require_once 'app/helpers/SessionHelper.php';
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Sách</h1>
    <a href="/Book/add" class="btn btn-primary">
        <i class="fas fa-plus-circle me-1"></i> Thêm sách mới
    </a>
</div>

<!-- Form tìm kiếm và lọc -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/Book" class="row g-3 align-items-center">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="search" name="search" placeholder="Tìm kiếm sách, tác giả, ISBN..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-2">
                <select id="subject_id" name="subject_id" class="form-select">
                    <option value="">Tất cả chủ đề</option>
                    <?php if (isset($subjects) && is_array($subjects)): ?>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?= htmlspecialchars($subject->id) ?>" <?= (($_GET['subject_id'] ?? '') == $subject->id) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($subject->name) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-2">
                <select id="sort" name="sort" class="form-select">
                    <option value="">Sắp xếp theo</option>
                    <option value="newest" <?= (($_GET['sort'] ?? '') == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="oldest" <?= (($_GET['sort'] ?? '') == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
                    <option value="name_asc" <?= (($_GET['sort'] ?? '') == 'name_asc') ? 'selected' : '' ?>>Tên (A-Z)</option>
                    <option value="name_desc" <?= (($_GET['sort'] ?? '') == 'name_desc') ? 'selected' : '' ?>>Tên (Z-A)</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="input-group">
                    <input type="number" name="min_year" placeholder="Từ năm" class="form-control" value="<?= htmlspecialchars($_GET['min_year'] ?? '') ?>">
                    <span class="input-group-text">-</span>
                    <input type="number" name="max_year" placeholder="Đến năm" class="form-control" value="<?= htmlspecialchars($_GET['max_year'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Bảng hiển thị danh sách sách -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 80px;">Ảnh bìa</th>
                    <th scope="col">Tên sách</th>
                    <th scope="col">Tác giả</th>
                    <th scope="col">Chủ đề</th>
                    <th scope="col" class="text-center">Số lượng</th>
                    <th scope="col" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td>
                                <img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($book->name) ?>" style="width: 50px; height: 70px; object-fit: cover;">
                            </td>
                            <td>
                                <a href="/Book/show/<?= $book->id ?>" class="fw-bold text-dark text-decoration-none">
                                    <?= htmlspecialchars($book->name) ?>
                                </a>
                                <small class="d-block text-muted">ISBN: <?= htmlspecialchars($book->ISBN ?? 'N/A') ?></small>
                            </td>
                            <td><?= htmlspecialchars($book->author) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($book->subject_name) ?></span></td>
                            <td class="text-center">
                                <span class="badge bg-success"><?= htmlspecialchars($book->available_copies) ?></span> / <?= htmlspecialchars($book->number_of_copies) ?>
                                <small class="d-block text-muted">(Có sẵn/Tổng)</small>
                            </td>
                            <td class="text-center">
                                <a href="/Book/show/<?= $book->id; ?>" class="btn btn-sm btn-outline-info" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                <a href="/Book/edit/<?= $book->id; ?>" class="btn btn-sm btn-outline-warning" title="Chỉnh sửa"><i class="fas fa-edit"></i></a>
                                <a href="/Book/delete/<?= $book->id; ?>" class="btn btn-sm btn-outline-danger" title="Xóa sách" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted p-5">
                            Không tìm thấy sách nào phù hợp với tiêu chí của bạn.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- (Tùy chọn) Thêm thanh phân trang ở đây nếu cần -->
    <!-- 
    <div class="card-footer">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">Sau</a></li>
            </ul>
        </nav>
    </div>
    -->
</div>
