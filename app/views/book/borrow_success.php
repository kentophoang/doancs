<?php
// Lấy kết quả mượn sách từ session
$results = $_SESSION['borrow_results'] ?? ['success' => [], 'failed' => []];
unset($_SESSION['borrow_results']); // Xóa session sau khi đã lấy
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body p-4 p-md-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                    <h2 class="card-title mb-3">Giao dịch Hoàn tất</h2>
                    <p class="card-text fs-5 text-muted">
                        Dưới đây là kết quả của yêu cầu mượn sách của bạn.
                    </p>

                    <hr class="my-4">

                    <?php if (!empty($results['success'])): ?>
                        <div class="text-start mb-4">
                            <h5 class="fw-bold text-success">Đã mượn thành công:</h5>
                            <ul class="list-group">
                                <?php foreach ($results['success'] as $bookName): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-book text-success me-2"></i>
                                        <?= htmlspecialchars($bookName) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($results['failed'])): ?>
                        <div class="text-start mb-4">
                            <h5 class="fw-bold text-danger">Mượn thất bại (do đã hết sách):</h5>
                            <ul class="list-group">
                                <?php foreach ($results['failed'] as $bookName): ?>
                                    <li class="list-group-item">
                                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                                        <?= htmlspecialchars($bookName) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="/Book" class="btn btn-outline-primary">Tiếp tục mượn sách</a>
                        <a href="/book/myBorrowedBooks" class="btn btn-primary">Xem sách đã mượn</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>