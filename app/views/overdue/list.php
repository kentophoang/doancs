<?php
// app/views/overdue/list.php
ob_start(); // Start output buffering
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Sách Quá hạn & Phí phạt</h1>
    <p class="text-muted">Theo dõi các cuốn sách đã quá hạn và phí phạt liên quan.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (!empty($overdueItems)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Giao dịch</th>
                                <th>Tên sách</th>
                                <th>Người mượn</th>
                                <th>Ngày mượn</th>
                                <th>Ngày đến hạn</th>
                                <th>Số ngày quá hạn</th>
                                <th>Phí phạt ước tính</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($overdueItems as $item): 
                                $days_overdue = (new DateTime(date('Y-m-d')))->diff(new DateTime($item->due_date))->days;
                                $estimated_fine = $days_overdue * 5000; // 5000 VND/ngày
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($item->id) ?></td>
                                    <td><?= htmlspecialchars($item->book_name) ?></td>
                                    <td><?= htmlspecialchars($item->borrower_fullname) ?> (<?= htmlspecialchars($item->borrower_username) ?>)</td>
                                    <td><?= htmlspecialchars($item->borrow_date) ?></td>
                                    <td><?= htmlspecialchars($item->due_date) ?></td>
                                    <td><?= htmlspecialchars($days_overdue) ?> ngày</td>
                                    <td><?= number_format($estimated_fine, 0, ',', '.') ?> đ</td>
                                    <td class="text-center">
                                        <a href="/Loan/adminReturn/<?= $item->id ?>" class="btn btn-success btn-sm mx-1" onclick="return confirm('Xác nhận trả sách này và thu phí phạt (nếu có)?');">
                                            <i class="fas fa-undo-alt"></i> Xử lý trả
                                        </a>
                                        </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-3">
                    Chưa có sách nào quá hạn.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php 
$main_content = ob_get_clean();
include 'app/views/shares/admin_layout.php';
?>