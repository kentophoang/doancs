<?php
// Lấy dữ liệu đã được truyền từ BookController
$loan = $data['loan'] ?? null;
$fine_amount = $data['fine_amount'] ?? 0;
$days_overdue = $data['days_overdue'] ?? 0;
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h2 class="mb-0">Xác nhận trả sách</h2>
                </div>
                <div class="card-body p-4 p-md-5">
                    <?php if ($loan): ?>
                        <div class="row align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="/<?= htmlspecialchars($loan->image ?? 'uploads/default-book.jpg') ?>" class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                            </div>
                            <div class="col-md-8">
                                <h4 class="fw-bold"><?= htmlspecialchars($loan->book_name) ?></h4>
                                <p class="text-muted mb-1">Tác giả: <?= htmlspecialchars($loan->author) ?></p>
                                <p class="text-muted">Ngày mượn: <?= date('d/m/Y', strtotime($loan->borrow_date)) ?></p>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Chi tiết trả sách:</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Ngày trả dự kiến:</span>
                                <strong><?= date('d/m/Y', strtotime($loan->due_date)) ?></strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Ngày trả thực tế:</span>
                                <strong><?= date('d/m/Y') ?></strong>
                            </li>
                            <?php if ($is_overdue): ?>
                            <li class="list-group-item d-flex justify-content-between text-danger">
                                <span>Số ngày quá hạn:</span>
                                <strong class="fw-bold"><?= $days_overdue ?> ngày</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between text-danger">
                                <span>Phí trễ hạn:</span>
                                <strong class="fw-bold"><?= number_format($fine_amount) ?> đ</strong>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <form action="/book/processReturn" method="POST" class="mt-4">
                            <input type="hidden" name="loan_id" value="<?= $loan->id ?>">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">Xác nhận trả sách</button>
                                <a href="/book/myBorrowedBooks" class="btn btn-secondary">Hủy bỏ</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">Không tìm thấy thông tin mượn sách.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
