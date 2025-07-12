<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 d-flex align-items-center justify-content-center bg-primary-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center text-primary fw-bold mb-4">Đăng ký</h2>
                        <p class="text-center text-muted">Tạo tài khoản mới ngay!</p>

                        <!-- Hiển thị thông báo lỗi nếu có -->
                        <?php if (isset($errors)): ?>
                            <div class="alert alert-danger text-center">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?= htmlspecialchars($err) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="/account/save" method="post">
                            <!-- Username & Full Name -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="username" placeholder="Nhập tên đăng nhập" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="fullname" placeholder="Nhập họ và tên" required>
                                </div>
                            </div>

                            <!-- Password & Confirm Password -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control rounded-3 shadow-sm" name="password" placeholder="Nhập mật khẩu" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Xác nhận mật khẩu</label>
                                    <input type="password" class="form-control rounded-3 shadow-sm" name="confirmpassword" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>

                            <!-- Nút đăng ký -->
                            <div class="d-grid">
                                <button class="btn btn-primary rounded-3 btn-lg shadow-sm" type="submit">Đăng ký</button>
                            </div>
                        </form>

                        <p class="text-center mt-3">Đã có tài khoản? 
                            <a href="/account/login" class="text-primary fw-bold">Đăng nhập ngay</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    /* Nền gradient xanh dương nhẹ nhàng */
    .bg-primary-light {
        background: linear-gradient(135deg, #a2d2ff, #62b6cb);
        height: 100vh;
    }

    /* Tiêu đề xanh dương nổi bật */
    .text-primary {
        color: #0077b6 !important;
    }

    /* Nút đăng ký màu xanh đậm */
    .btn-primary {
        background: #0077b6;
        border: none;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
    }

    .btn-primary:hover {
        background: #005f8e;
        transform: scale(1.05);
    }

    /* Input có viền mềm mại, bóng đổ nhẹ */
    .form-control {
        border: 2px solid #0077b6;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #005f8e;
        box-shadow: 0 0 10px rgba(0, 119, 182, 0.3);
    }
</style>
