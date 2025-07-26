<?php
// Lấy email và token từ session
$email = $_SESSION['verification_email'] ?? 'email của bạn';
$demo_token = $_SESSION['verification_token_for_demo'] ?? null;
?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body p-4 p-md-5">
                    <i class="fas fa-envelope-open-text fa-4x text-primary mb-4"></i>
                    <h2 class="card-title mb-3">Xác thực Email của bạn</h2>
                    <p class="card-text fs-5 text-muted">
                        Chúng tôi đã gửi một liên kết xác thực đến địa chỉ email:
                    </p>
                    <p class="fs-5 fw-bold text-dark"><?= htmlspecialchars($email) ?></p>
                    
                    <?php
                    // Chỉ hiển thị khối này nếu có token trong session (dành cho demo)
                    if ($demo_token):
                    ?>
                        <div class="alert alert-warning mt-4">
                            <h5 class="alert-heading"><i class="fas fa-flask"></i> Dành cho DEMO</h5>
                            <p class="mb-0">Để xác thực ngay mà không cần kiểm tra email, hãy nhấp vào liên kết dưới đây:</p>
                            <hr>
                            <a href="/account/verify?token=<?= htmlspecialchars($demo_token) ?>" class="btn btn-success fw-bold">
                                Kích hoạt tài khoản ngay
                            </a>
                        </div>
                    <?php 
                        // Xóa token khỏi session sau khi đã hiển thị để tránh dùng lại
                        unset($_SESSION['verification_token_for_demo']);
                    endif; 
                    ?>

                    <hr class="my-4">
                    <p class="text-muted small">
                        Trong môi trường thực tế, bạn sẽ cần kiểm tra hộp thư (và cả thư mục Spam) để tìm email này.
                    </p>
                    <a href="/" class="btn btn-outline-secondary mt-3">Quay về Trang chủ</a>
                </div>
            </div>
        </div>
    </div>
</div>
