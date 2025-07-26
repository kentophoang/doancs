<?php
// File này được gọi từ BookController, biến $book đã có sẵn.
?>

<div class="container my-5">
    <?php if ($book): ?>
        <div class="card shadow-lg border-0">
            <div class="card-body p-4 p-md-5">
                <div class="row g-5">
                    <!-- Cột ảnh bìa sách -->
                    <div class="col-lg-4 text-center">
                        <img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" 
                             class="img-fluid rounded shadow" 
                             alt="<?= htmlspecialchars($book->name) ?>"
                             style="max-height: 450px; object-fit: cover;">
                    </div>

                    <!-- Cột thông tin chi tiết -->
                    <div class="col-lg-8 d-flex flex-column">
                        <h1 class="display-6 fw-bold mb-2"><?= htmlspecialchars($book->name) ?></h1>
                        <p class="h5 text-muted mb-4">bởi <?= htmlspecialchars($book->author) ?></p>

                        <div class="mb-4">
                            <span class="badge bg-secondary me-2"><?= htmlspecialchars($book->subject_name ?? 'Chưa phân loại') ?></span>
                            <span class="badge bg-info text-dark">Năm XB: <?= htmlspecialchars($book->publication_year) ?></span>
                        </div>

                        <p class="lead"><?= htmlspecialchars($book->description) ?></p>

                        <hr class="my-4">

                        <!-- Bảng thông tin bổ sung -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Nhà xuất bản:</strong> <?= htmlspecialchars($book->publisher ?? 'N/A') ?></p>
                                <p><strong>Mã ISBN:</strong> <?= htmlspecialchars($book->ISBN ?? 'N/A') ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Vị trí:</strong> <span class="fw-bold text-primary"><?= htmlspecialchars($book->location ?? 'Chưa cập nhật') ?></span></p>
                                <p><strong>Tình trạng:</strong> 
                                    <?php if ($book->available_copies > 0): ?>
                                        <span class="fw-bold text-success">Còn sách (<?= $book->available_copies ?> quyển)</span>
                                    <?php else: ?>
                                        <span class="fw-bold text-danger">Đã hết sách</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>

                        <!-- Khu vực hành động (Mượn sách) -->
                        <div class="mt-auto">
                            <?php if (SessionHelper::isLoggedIn()): ?>
                                <?php if ($book->available_copies > 0): ?>
                                    <a href="/book/addToCart/<?= $book->id ?>" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-plus-circle me-2"></i> Thêm vào Phiếu mượn
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-lg w-100" disabled>Đã hết sách</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class="alert alert-warning text-center">
                                    Vui lòng <a href="/account/login">đăng nhập</a> để mượn sách.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger text-center">
            <h4>Không tìm thấy thông tin sách!</h4>
        </div>
    <?php endif; ?>
</div>
