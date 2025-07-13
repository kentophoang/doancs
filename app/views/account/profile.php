<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Hồ sơ người dùng</h2>
        </div>
        <div class="card-body">
            <?php if (isset($account)): ?>
                <form action="/account/updateProfile" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($account->username) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="fullname" class="form-label">Họ và tên:</label>
                        <input type="text" id="fullname" name="fullname" class="form-control" value="<?= htmlspecialchars($account->fullname) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò:</label>
                        <input type="text" id="role" name="role" class="form-control" value="<?= htmlspecialchars($account->role) ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="profession" class="form-label">Nghề nghiệp/Vị trí:</label>
                        <input type="text" id="profession" name="profession" class="form-control" value="<?= htmlspecialchars($account->profession ?? '') ?>">
                    </div>
                    <div class="mb-3">
                        <label for="industry" class="form-label">Ngành nghề/Lĩnh vực:</label>
                        <input type="text" id="industry" name="industry" class="form-control" value="<?= htmlspecialchars($account->industry ?? '') ?>">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Cập nhật hồ sơ</button>
                        <a href="/Book" class="btn btn-secondary ml-2">Quay lại danh sách sách</a>
                    </div>
                </form>
            <?php else: ?>
                <div class="alert alert-danger text-center">
                    <h4>Không tìm thấy thông tin hồ sơ!</h4>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: #007bff;
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }
</style>