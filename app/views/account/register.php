<?php
// Lấy lại dữ liệu đã nhập và lỗi từ session (nếu có)
$errors = $_SESSION['registration_errors'] ?? [];
$old_data = $_SESSION['POST_data'] ?? [];

// Xóa session sau khi đã lấy dữ liệu để tránh hiển thị lại ở lần sau
unset($_SESSION['registration_errors']);
unset($_SESSION['POST_data']);
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4 p-md-5">
                    <h2 class="card-title text-center fw-bold mb-3">Tạo tài khoản mới</h2>
                    <p class="text-center text-muted mb-4">Tham gia cộng đồng LIBSMART ngay hôm nay!</p>

                    <!-- Hiển thị khối thông báo lỗi (nếu có) -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <!-- Form đăng ký trỏ đến action /account/register -->
                    <form action="/account/register" method="POST">
                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Nhập họ và tên của bạn" value="<?= htmlspecialchars($old_data['fullname'] ?? '') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Chọn một tên đăng nhập" value="<?= htmlspecialchars($old_data['username'] ?? '') ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Địa chỉ Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email để xác thực tài khoản" value="<?= htmlspecialchars($old_data['email'] ?? '') ?>" required>
                        </div>

                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="confirmpassword" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" required>
                            </div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Đăng ký</button>
                        </div>

                        <div class="text-center mt-3">
                            <p class="mb-0">Đã có tài khoản? <a href="/account/login">Đăng nhập ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
