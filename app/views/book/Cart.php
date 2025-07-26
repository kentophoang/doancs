<div class="container my-5">
    <h1 class="mb-4">Phiếu mượn sách của bạn</h1>

    <?php if (!empty($data['booksInCart'])): ?>
        <form action="/book/processBorrow" method="POST">
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 100px;">Ảnh bìa</th>
                                <th>Tên sách</th>
                                <th>Tác giả</th>
                                <th class="text-center">Tình trạng</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['booksInCart'] as $book): ?>
                                <tr>
                                    <td><img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" class="img-fluid rounded" style="width: 60px; height: 90px; object-fit: cover;"></td>
                                    <td><strong><?= htmlspecialchars($book->name) ?></strong></td>
                                    <td><?= htmlspecialchars($book->author) ?></td>
                                    <td class="text-center">
                                        <?php if($book->available_copies > 0): ?>
                                            <span class="badge bg-success">Còn sách</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Đã hết</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/book/removeFromCart/<?= $book->id ?>" class="btn btn-sm btn-outline-danger" title="Xóa khỏi phiếu mượn">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer text-end">
                    <a href="/Book" class="btn btn-secondary">Tiếp tục chọn sách</a>
                    <button type="submit" class="btn btn-primary">Xác nhận mượn</button>
                </div>
            </div>
        </form>
    <?php else: ?>
        <div class="text-center p-5 border rounded">
            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">Giỏ mượn của bạn đang trống.</h4>
            <a href="/Book" class="btn btn-primary mt-3">Bắt đầu chọn sách</a>
        </div>
    <?php endif; ?>
</div>
