<?php
// Lấy email từ session để hiển thị cho người dùng
$email = $_SESSION['verification_email'] ?? 'email của bạn';
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body p-4 p-md-5">
                    <i class="fas fa-envelope-open-text fa-4x text-primary mb-4"></i>
                    <h2 class="card-title mb-3">Xác thực Email của bạn</h2>
                    <p class="card-text fs-5 text-muted">
                        Cảm ơn bạn đã đăng ký! Chúng tôi đã gửi một liên kết xác thực đến địa chỉ email:
                    </p>
                    <p class="fs-5 fw-bold text-dark"><?= htmlspecialchars($email) ?></p>
                    <p class="card-text text-muted mt-4">
                        Vui lòng nhấp vào liên kết trong email đó để kích hoạt tài khoản của bạn.
                    </p>
                    <hr class="my-4">
                    <p class="text-muted small">
                        Không nhận được email? Vui lòng kiểm tra thư mục Spam (Thư rác) hoặc thử <a href="/account/register">đăng ký lại</a>.
                    </p>
                    <a href="/account/login" class="btn btn-outline-primary mt-3">Về trang Đăng nhập</a>
                </div>
            </div>
        </div>
    </div>
</div>
