<?php
// app/views/reservation/manage.php
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Quản lý Đặt trước Sách</h1>
    <p class="text-muted">Đây là trang quản lý các yêu cầu đặt trước sách từ thành viên.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p>Nội dung quản lý các yêu cầu đặt trước sách sẽ hiển thị ở đây.</p>
            <p>Các chức năng: Duyệt đặt trước, Hủy đặt trước, Lọc theo trạng thái, ngày đặt.</p>
            <?php if (empty($reservations)): ?>
                <div class="alert alert-info text-center">
                    Chưa có yêu cầu đặt trước sách nào.
                </div>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </div>
</div>