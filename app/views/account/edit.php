<?php
// app/views/account/edit.php
// View để admin chỉnh sửa thông tin tài khoản người dùng
ob_start(); // Start output buffering
?>

<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Chỉnh sửa tài khoản: <?= htmlspecialchars($account->fullname ?? 'N/A') ?></h1>
    <p class="text-muted">Cập nhật thông tin và vai trò của người dùng.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($account)): ?>
                <form action="/Account/edit/<?= $account->id ?>" method="POST">
                    <div class="form-group mb-3">
                        <label for="username">Tên đăng nhập:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($account->username) ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="fullname">Họ và tên:</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($_POST['fullname'] ?? $account->fullname) ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="profession">Nghề nghiệp/Vị trí:</label>
                        <input type="text" id="profession" name="profession" class="form-control" value="<?= htmlspecialchars($_POST['profession'] ?? $account->profession ?? '') ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="industry">Ngành nghề/Lĩnh vực:</label>
                        <input type="text" id="industry" name="industry" class="form-control" value="<?= htmlspecialchars($_POST['industry'] ?? $account->industry ?? '') ?>">
                    </div>
                    <div class="form-group mb-3">
                        <label for="role">Vai trò:</label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="user" <?= (($account->role ?? 'user') === 'user') ? 'selected' : '' ?>>Người dùng</option>
                            <option value="admin" <?= (($account->role ?? 'user') === 'admin') ? 'selected' : '' ?>>Quản trị viên</option>
                        </select>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary mx-1">Cập nhật</button>
                        <a href="/Account/manage" class="btn btn-secondary mx-1">Quay lại</a>
                    </div>
                </form>
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