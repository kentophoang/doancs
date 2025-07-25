<section class="vh-100 d-flex align-items-center justify-content-center bg-primary-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-5">
                        <h2 class="text-center text-primary fw-bold mb-4">Đăng ký tài khoản Thư viện</h2>
                        <p class="text-center text-muted">Tạo tài khoản mới ngay để bắt đầu mượn sách!</p>

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
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="username" placeholder="Nhập tên đăng nhập" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="fullname" placeholder="Nhập họ và tên" required value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                                </div>
                            </div>

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

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Nghề nghiệp/Vị trí</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="profession" placeholder="VD: Lập trình viên, Kế toán..." value="<?= htmlspecialchars($_POST['profession'] ?? '') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Ngành nghề/Lĩnh vực</label>
                                    <input type="text" class="form-control rounded-3 shadow-sm" name="industry" placeholder="VD: CNTT, Tài chính, Y tế..." value="<?= htmlspecialchars($_POST['industry'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary rounded-3 btn-lg shadow-sm" type="submit">Đăng ký tài khoản</button>
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

<style>
    .bg-primary-light {
        background: linear-gradient(135deg, #a2d2ff, #62b6cb);
        height: 100vh;
    }
    .text-primary {
        color: #0077b6 !important;
    }
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
    .form-control {
        border: 2px solid #0077b6;
        transition: all 0.3s ease-in-out;
    }
    .form-control:focus {
        border-color: #005f8e;
        box-shadow: 0 0 10px rgba(0, 119, 182, 0.3);
    }
</style>