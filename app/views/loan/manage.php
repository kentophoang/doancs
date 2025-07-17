<?php
// app/views/loan/manage.php
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Quản lý Lưu hành Sách</h1>
    <p class="text-muted">Đây là trang quản lý các giao dịch mượn/trả sách.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <p>Nội dung quản lý các giao dịch lưu hành sách sẽ hiển thị ở đây.</p>
            <p>Các chức năng: Thêm giao dịch, Sửa, Xóa, Lọc theo ngày, trạng thái, người dùng, sách.</p>
            <?php if (empty($loans)): ?>
                <div class="alert alert-info text-center">
                    Chưa có giao dịch lưu hành sách nào.
                </div>
            <?php else: ?>
                <?php endif; ?>
        </div>
    </div>
</div>