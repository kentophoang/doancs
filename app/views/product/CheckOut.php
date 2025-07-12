<?php include 'app/views/shares/header.php'; ?>

<div class="checkout-container">
    <div class="checkout-content">
        <h1 class="text-center">💳 Thanh toán</h1>
        <form action="/Product/processCheckout" method="POST">
            <div class="form-group">
                <label for="name">Họ tên:</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" id="phone" name="phone" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <textarea id="address" name="address" class="form-control" rows="3" required></textarea>
            </div>

            <div class="text-center mt-3">
                <button type="submit" class="btn btn-primary">Thanh toán</button>
                <a href="/Product/cart" class="btn btn-secondary">Quay lại giỏ hàng</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    /* Toàn bộ nền trang có hiệu ứng gradient */
    .checkout-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(to bottom right, #d4faff, #b3ecff); /* Màu nền giống ảnh bạn gửi */
        padding: 20px;
    }

    /* Khung form */
    .checkout-content {
        width: 100%;
        max-width: 500px;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease-in-out;
    }

    /* Hiệu ứng khi rê chuột vào form */
    .checkout-content:hover {
        transform: scale(1.02);
    }

    /* Căn chỉnh form đẹp hơn */
    .form-group {
        margin-bottom: 18px;
    }

    .form-control {
        width: 100%;
        padding: 12px;
        border: 2px solid #ccc;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease-in-out;
    }

    /* Hiệu ứng khi nhập vào input */
    .form-control:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
    }

    /* Nút bấm */
    .btn {
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 8px;
        transition: background 0.3s ease-in-out;
    }

    /* Hiệu ứng hover cho nút */
    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary:hover {
        background: #6c757d;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .checkout-content {
            width: 90%;
        }
    }
</style>
