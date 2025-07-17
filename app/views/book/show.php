<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Chi tiết sách</h2>
        </div>
        <div class="card-body">
            <?php if ($book): // Changed $product to $book ?>
                <div class="row">
                    <div class="col-md-6">
                        <?php if ($book->image): // Changed $product to $book ?>
                            <img src="/<?php echo htmlspecialchars($book->image, ENT_QUOTES, 'UTF-8'); ?>"
                                class="img-fluid rounded"
                                alt="<?php echo htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php else: ?>
                            <img src="/images/no-image.png"
                                class="img-fluid rounded"
                                alt="Không có ảnh">
                        <?php endif; ?>
                    </div>
                    <div class="col-md-6">
                        <h3 class="card-title text-dark font-weight-bold">
                            <?php echo htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8'); ?>
                        </h3>
                        <p class="card-text">
                            <?php echo nl2br(htmlspecialchars($book->description, ENT_QUOTES, 'UTF-8')); ?>
                        </p>
                        <p><strong>Tác giả:</strong> <?php echo htmlspecialchars($book->author, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Nhà xuất bản:</strong> <?php echo htmlspecialchars($book->publisher, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Năm xuất bản:</strong> <?php echo htmlspecialchars($book->publication_year, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>ISBN:</strong> <?php echo htmlspecialchars($book->ISBN, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Chủ đề:</strong>
                            <span class="badge bg-info text-white">
                                <?php echo !empty($book->subject_name) ?
                                    htmlspecialchars($book->subject_name, ENT_QUOTES, 'UTF-8') : 'Chưa có chủ đề'; ?>
                            </span>
                        </p>
                        <p><strong>Tổng số bản sao:</strong> <?php echo htmlspecialchars($book->number_of_copies, ENT_QUOTES, 'UTF-8'); ?></p>
                        <p><strong>Số bản có sẵn:</strong> <?php echo htmlspecialchars($book->available_copies, ENT_QUOTES, 'UTF-8'); ?></p>

                        <div class="mt-4">
                            <?php if (isset($_SESSION['username'])): ?>
                                <?php if ($book->available_copies > 0): ?>
                                    <a href="/Book/borrow/<?php echo $book->id; ?>" class="btn btn-success px-4">
                                        Mượn sách
                                    </a>
                                <?php else: ?>
                                    <button class="btn btn-secondary px-4" disabled>Hết sách</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-danger font-weight-bold">Bạn cần <a href="/account/login/">đăng nhập</a> để mượn sách.</p>
                            <?php endif; ?>

                            <a href="/Book/index" class="btn btn-secondary px-4 ml-2">Quay lại danh sách</a>
                        </div>

                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>Không tìm thấy sách!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'app/views/shares/footer.php'; ?>
<style>
    .card-header {
        background-color: #007bff; /* Primary color */
        color: white;
    }
    .badge-info {
        background-color: #17a2b8 !important; /* Bootstrap info color */
    }
    .btn-success {
        background-color: #28a745; /* Bootstrap success color */
        border-color: #28a745;
    }
    .btn-secondary {
        background-color: #6c757d; /* Bootstrap secondary color */
        border-color: #6c757d;
    }
</style>