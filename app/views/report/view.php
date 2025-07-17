<?php
// app/views/report/view.php
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Tạo Báo cáo</h1>
    <p class="text-muted">Tạo và quản lý các báo cáo thống kê của thư viện.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p>Nội dung tạo và xem các báo cáo thư viện sẽ hiển thị ở đây.</p>
            <p>Các loại báo cáo: Sách quá hạn, thành viên hoạt động, sách phổ biến, doanh thu phí phạt, v.v.</p>
            <?php if (empty($reports)): ?>
                <div class="alert alert-info text-center">
                    Chưa có báo cáo nào được tạo.
                </div>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </div>
</div>