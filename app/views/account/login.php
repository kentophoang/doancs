<?php
// Giao diện này không cần layout, nó sẽ được nhúng vào public_layout.php
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h2 class="card-title text-center mb-4">Đăng nhập</h2>

                    <?php
                    // Hiển thị thông báo lỗi chung (sai mật khẩu, v.v.)
                    if (isset($_SESSION['login_error'])) {
                        echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['login_error']) . '</div>';
                        unset($_SESSION['login_error']);
                    }
                    // Hiển thị thông báo lỗi từ URL (chưa xác thực, v.v.)
                    if (isset($_GET['error'])) {
                        echo '<div class="alert alert-warning">' . htmlspecialchars($_GET['error']) . '</div>';
                    }
                    // Hiển thị thông báo thành công từ URL (đăng ký thành công, xác thực thành công)
                    if (isset($_GET['message'])) {
                        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
                    }
                    ?>
                    
                    <form action="/account/checkLogin" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                        </div>
                        <div class="text-center mt-3">
                            <p class="mb-0">Chưa có tài khoản? <a href="/account/register">Đăng ký ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
