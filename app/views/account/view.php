<?php
// app/views/account/view.php
// View để xem chi tiết thông tin tài khoản (cho admin)
ob_start(); // Start output buffering
?>

<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Chi tiết tài khoản: <?= htmlspecialchars($account->fullname ?? 'N/A') ?></h1>
    <p class="text-muted">Thông tin chi tiết về tài khoản người dùng.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (isset($account)): ?>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Thông tin cơ bản</h5>
                        <p><strong>ID:</strong> <?= htmlspecialchars($account->id) ?></p>
                        <p><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($account->username) ?></p>
                        <p><strong>Họ và tên:</strong> <?= htmlspecialchars($account->fullname) ?></p>
                        <p><strong>Vai trò:</strong> <?= htmlspecialchars($account->role) ?></p>
                        <p><strong>Nghề nghiệp:</strong> <?= htmlspecialchars($account->profession ?? 'Không có') ?></p>
                        <p><strong>Ngành nghề:</strong> <?= htmlspecialchars($account->industry ?? 'Không có') ?></p>
                        <p><strong>Ngày tạo:</strong> <?= htmlspecialchars($account->created_at ?? 'N/A') ?></p>
                        </div>
                    <div class="col-md-6">
                        <h5>Hoạt động & Lịch sử</h5>
                        <p>Số sách đang mượn: <strong>0</strong></p> <p>Số sách quá hạn: <strong>0</strong></p> <p>Tổng phí phạt: <strong>0 VND</strong></p> </div>
                </div>

                <div class="mt-4 text-center">
                    <a href="/Account/edit/<?= $account->id ?>" class="btn btn-warning mx-1">
                        <i class="fas fa-edit"></i> Sửa tài khoản
                    </a>
                    <a href="/Account/delete/<?= $account->id ?>" class="btn btn-danger mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                        <i class="fas fa-trash-alt"></i> Xóa tài khoản
                    </a>
                    <a href="/Account/manage" class="btn btn-secondary mx-1">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>Không tìm thấy thông tin tài khoản này!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$main_content = ob_get_clean();
include 'app/views/shares/admin_layout.php';
?>