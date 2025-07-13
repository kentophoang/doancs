<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">📚 Sách bạn đã mượn</h2>

    <?php if (!empty($borrowedBooks)): ?>
        <div class="row">
            <?php foreach ($borrowedBooks as $borrowedBook): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($borrowedBook->book_name) ?></h5>
                            <p class="card-text"><strong>Tác giả:</strong> <?= htmlspecialchars($borrowedBook->author) ?></p>
                            <p class="card-text"><strong>Ngày mượn:</strong> <?= htmlspecialchars($borrowedBook->borrow_date) ?></p>
                            <p class="card-text"><strong>Ngày đến hạn:</strong> <?= htmlspecialchars($borrowedBook->due_date) ?></p>
                            <p class="card-text"><strong>Trạng thái:</strong>
                                <span class="badge <?= ($borrowedBook->status == 'overdue') ? 'badge-danger' : (($borrowedBook->status == 'returned') ? 'badge-success' : 'badge-info') ?>">
                                    <?= htmlspecialchars($borrowedBook->status) ?>
                                </span>
                            </p>
                            <?php if ($borrowedBook->status == 'borrowed'): ?>
                                <a href="/Book/returnBook/<?= $borrowedBook->book_id ?>" class="btn btn-success btn-sm mt-2" onclick="return confirm('Bạn có chắc chắn muốn trả sách này?');">Trả sách</a>
                            <?php endif; ?>
                            <?php if ($borrowedBook->fine_amount > 0): ?>
                                <p class="card-text text-danger mt-2"><strong>Phí trễ hạn:</strong> <?= number_format($borrowedBook->fine_amount, 0, ',', '.') ?> đ</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info text-center">
            Bạn chưa mượn cuốn sách nào.
            <a href="/Book/" class="alert-link">Hãy khám phá thư viện!</a>
        </div>
    <?php endif; ?>

    <div class="text-center mt-4">
        <a href="/Book/" class="btn btn-primary">Quay lại danh sách sách</a>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>