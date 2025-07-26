<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Sách đang mượn</h1>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Sách</th>
                        <th scope="col">Ngày mượn</th>
                        <th scope="col">Ngày hết hạn</th>
                        <th scope="col" class="text-center">Trạng thái</th>
                        <th scope="col" class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($borrowedBooks)): ?>
                        <?php foreach ($borrowedBooks as $loan): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/<?= htmlspecialchars($loan->image ?? 'uploads/default-book.jpg') ?>" class="img-fluid rounded me-3" style="width: 50px; height: 70px; object-fit: cover;">
                                        <div>
                                            <a href="/book/show/<?= $loan->book_id ?>" class="fw-bold text-dark text-decoration-none">
                                                <?= htmlspecialchars($loan->book_name) ?>
                                            </a>
                                            <small class="d-block text-muted">
                                                Tác giả: <?= htmlspecialchars($loan->author ?? 'Không rõ') ?>
                                            </small>
                                        </div>
                                    </div>
                                </td>
                                <td><?= date('d/m/Y', strtotime($loan->borrow_date)) ?></td>
                                <td><?= date('d/m/Y', strtotime($loan->due_date)) ?></td>
                                <td class="text-center">
                                    <?php
                                        $is_overdue = strtotime($loan->due_date) < time() && $loan->status !== 'returned';
                                        if ($loan->status === 'returned') {
                                            echo '<span class="badge bg-secondary">Đã trả</span>';
                                        } elseif ($is_overdue) {
                                            echo '<span class="badge bg-danger">Quá hạn</span>';
                                        } else {
                                            echo '<span class="badge bg-primary">Đang mượn</span>';
                                        }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($loan->status !== 'returned'): ?>
                                        <a href="/book/returnBook/<?= $loan->id ?>" class="btn btn-sm btn-outline-success">
                                             Trả sách
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted p-5">
                                <i class="fas fa-book-reader fa-3x mb-3"></i>
                                <p class="mb-0">Bạn chưa mượn cuốn sách nào.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
