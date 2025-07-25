<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 d-flex align-items-center justify-content-center bg-primary-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="card shadow-lg border-0 rounded-4" style="background: white;">
                    <div class="card-body p-5">
                        <h2 class="text-center text-primary fw-bold mb-4">Đăng nhập</h2>
                        <p class="text-center text-muted">Vui lòng nhập thông tin tài khoản</p>

                        <?php if (isset($_SESSION['login_error'])): ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                            </div>
                        <?php endif; ?>

                        <form action="/account/checklogin" method="post">
                            <div class="mb-3">
                                <label class="form-label">Tên đăng nhập</label>
                                <input type="text" name="username" class="form-control rounded-3 shadow-sm" placeholder="Nhập tên đăng nhập" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mật khẩu</label>
                                <input type="password" name="password" class="form-control rounded-3 shadow-sm" placeholder="Nhập mật khẩu" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <a href="#" class="text-primary fw-bold">Quên mật khẩu?</a>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary rounded-3 btn-lg shadow-sm" type="submit">Đăng nhập</button>
                            </div>
                        </form>

                        <p class="text-center mt-3">Chưa có tài khoản? 
                            <a href="/account/register" class="text-primary fw-bold">Đăng ký ngay</a>
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

    /* Nút đăng nhập màu xanh đậm */
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

    /* Tăng độ nổi bật cho input */
    .form-control {
        border: 2px solid #0077b6;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #005f8e;
        box-shadow: 0 0 10px rgba(0, 119, 182, 0.3);
    }
</style>