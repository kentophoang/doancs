<?php
// app/views/analytics/view.php
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Phân tích & Thống kê</h1>
    <p class="text-muted">Theo dõi hiệu suất và xu hướng của thư viện.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p>Nội dung phân tích và thống kê thư viện sẽ hiển thị ở đây.</p>
            <p>Các biểu đồ: Sách mượn theo ngày/tháng, thành viên hoạt động, phân bổ theo chủ đề, v.v.</p>
            <?php if (empty($analyticsData)): ?>
                <div class="alert alert-info text-center">
                    Chưa có dữ liệu phân tích để hiển thị.
                </div>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </div>
</div>