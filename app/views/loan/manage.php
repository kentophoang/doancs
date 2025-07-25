<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Quản lý Lưu hành Sách</h1>
    <p class="text-muted">Đây là trang quản lý các giao dịch mượn/trả sách.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/Loan/manage" class="form-row align-items-center mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-icon-bg"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="search" name="search" placeholder="Tìm kiếm sách, thành viên, ISBN..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select id="status" name="status" class="form-control custom-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="borrowed" <?= (isset($_GET['status']) && $_GET['status'] == 'borrowed') ? 'selected' : '' ?>>Đang mượn</option>
                        <option value="returned" <?= (isset($_GET['status']) && $_GET['status'] == 'returned') ? 'selected' : '' ?>>Đã trả</option>
                        <option value="overdue" <?= (isset($_GET['status']) && $_GET['status'] == 'overdue') ? 'selected' : '' ?>>Quá hạn</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block filter-btn">Lọc</button>
                </div>
            </form>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success mt-3">
                    <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger mt-3">
                    <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($loans)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Giao dịch</th>
                                <th>Tên sách</th>
                                <th>Người mượn</th>
                                <th>Ngày mượn</th>
                                <th>Ngày đến hạn</th>
                                <th>Ngày trả</th>
                                <th>Trạng thái</th>
                                <th>Phí phạt</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($loans as $loan): ?>
                                <tr>
                                    <td><?= htmlspecialchars($loan->id) ?></td>
                                    <td><?= htmlspecialchars($loan->book_name) ?></td>
                                    <td><?= htmlspecialchars($loan->borrower_fullname) ?> (<?= htmlspecialchars($loan->borrower_username) ?>)</td>
                                    <td><?= htmlspecialchars($loan->borrow_date) ?></td>
                                    <td><?= htmlspecialchars($loan->due_date) ?></td>
                                    <td><?= htmlspecialchars($loan->return_date ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= ($loan->status == 'overdue') ? 'badge-danger' : 
                                               (($loan->status == 'returned') ? 'badge-success' : 'badge-info') ?>">
                                            <?= htmlspecialchars($loan->status) ?>
                                        </span>
                                    </td>
                                    <td><?= number_format($loan->fine_amount, 0, ',', '.') ?> đ</td>
                                    <td class="text-center">
                                        <a href="/Loan/view/<?= $loan->id ?>" class="btn btn-info btn-sm mx-1">
                                            <i class="fas fa-eye"></i> Xem
                                        </a>
                                        <?php if ($loan->status == 'borrowed' || $loan->status == 'overdue'): ?>
                                            <a href="/Loan/adminReturn/<?= $loan->id ?>" class="btn btn-success btn-sm mx-1" onclick="return confirm('Xác nhận trả sách này?');">
                                                <i class="fas fa-undo-alt"></i> Trả sách
                                            </a>
                                        <?php endif; ?>
                                        <a href="/Loan/delete/<?= $loan->id ?>" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa giao dịch này?');">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-3">
                    Chưa có giao dịch lưu hành sách nào theo tiêu chí tìm kiếm.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
