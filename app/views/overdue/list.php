<?php
// app/views/overdue/list.php
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Sách Quá hạn & Phí phạt</h1>
    <p class="text-muted">Theo dõi các cuốn sách đã quá hạn và phí phạt liên quan.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p>Nội dung danh sách sách quá hạn và các khoản phí phạt sẽ hiển thị ở đây.</p>
            <p>Các chức năng: Lọc theo thành viên, sách, ngày quá hạn; Gửi nhắc nhở.</p>
            <?php if (empty($overdueItems)): ?>
                <div class="alert alert-info text-center">
                    Chưa có sách nào quá hạn.
                </div>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </div>
</div>