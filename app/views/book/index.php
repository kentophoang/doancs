<?php
// Lấy tên chủ đề hiện tại để hiển thị (nếu có)
$pageTitle = "Khám phá Sách";
if (isset($currentSubject) && $currentSubject) {
    $pageTitle = "Sách thuộc chủ đề: " . htmlspecialchars($currentSubject->name);
}
?>

<div class="container my-5">
    <div class="row">
        <!-- Cột bộ lọc -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4 sticky-top" style="top: 80px;">
                <div class="card-header bg-light fw-bold">
                    <i class="fas fa-filter me-2"></i> Bộ lọc
                </div>
                <div class="card-body">
                    <form method="GET" action="/Book">
                        <!-- Tìm kiếm -->
                        <div class="mb-3">
                            <label for="search" class="form-label small">Tìm kiếm</label>
                            <input type="text" id="search" name="search" placeholder="Tên sách, tác giả..." class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        </div>

                        <!-- Lọc theo chủ đề -->
                        <div class="mb-3">
                            <label for="subject_id" class="form-label small">Chủ đề</label>
                            <select id="subject_id" name="subject_id" class="form-select form-select-sm">
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

                        <!-- Sắp xếp -->
                        <div class="mb-3">
                             <label for="sort" class="form-label small">Sắp xếp</label>
                            <select id="sort" name="sort" class="form-select form-select-sm">
                                <option value="">Mặc định</option>
                                <option value="newest" <?= (($_GET['sort'] ?? '') == 'newest') ? 'selected' : '' ?>>Mới nhất</option>
                                <option value="oldest" <?= (($_GET['sort'] ?? '') == 'oldest') ? 'selected' : '' ?>>Cũ nhất</option>
                                <option value="name_asc" <?= (($_GET['sort'] ?? '') == 'name_asc') ? 'selected' : '' ?>>Tên (A-Z)</option>
                                <option value="name_desc" <?= (($_GET['sort'] ?? '') == 'name_desc') ? 'selected' : '' ?>>Tên (Z-A)</option>
                            </select>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-sm">Áp dụng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cột hiển thị sách -->
        <div class="col-lg-9">
            <h2 class="mb-4"><?= $pageTitle ?></h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php if (!empty($books)): ?>
                    <?php foreach ($books as $book): ?>
                        <div class="col">
                            <div class="card book-card h-100 shadow-sm">
                                <a href="/Book/show/<?= $book->id ?>">
                                    <img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" class="card-img-top" alt="<?= htmlspecialchars($book->name) ?>">
                                </a>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title flex-grow-1">
                                        <a href="/Book/show/<?= $book->id ?>" class="text-decoration-none text-dark" title="<?= htmlspecialchars($book->name) ?>">
                                            <?= htmlspecialchars($book->name) ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted mb-2"><small><?= htmlspecialchars($book->author) ?></small></p>
                                </div>
                                <div class="card-footer bg-white border-top-0 pt-0">
                                    <?php if ($book->available_copies > 0): ?>
                                        <a href="/book/addToCart/<?= $book->id ?>" class="btn btn-sm btn-outline-primary w-100">
                                            <i class="fas fa-plus-circle me-1"></i> Thêm vào giỏ mượn
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary w-100" disabled>Đã hết sách</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-3x mb-3"></i>
                            <p class="mb-0">Không tìm thấy sách nào phù hợp với tiêu chí của bạn.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Phân trang (Pagination) -->
            <nav aria-label="Page navigation" class="mt-5 d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<style>
    .book-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: none;
        border-radius: 0.5rem;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
    }
    .book-card .card-img-top {
        height: 350px;
        object-fit: cover;
        border-radius: 0.5rem 0.5rem 0 0;
    }
    .book-card .card-title {
        font-size: 1rem;
        font-weight: 600;
        /* Giới hạn 2 dòng chữ */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
