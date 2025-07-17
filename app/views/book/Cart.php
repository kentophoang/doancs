<?php include 'app/views/shares/header.php'; ?>
<div class="cart-container">
    <div class="cart-content">
        <h1 class="text-center">🛒 Giỏ hàng</h1>

        <?php if (!empty($cart)): ?>
            <div class="card p-4 shadow-lg">
                <ul class="list-group">
                    <?php 
                    $total = 0;
                    foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center cart-item">
                            <div class="cart-item-details">
                                <h5><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>

                                <?php if (!empty($item['image'])): ?>
                                    <img src="/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                        alt="Book Image" class="img-thumbnail product-img">
                                <?php endif; ?>

                                <p>Giá: <strong><?php echo number_format($item['price'], 0, ',', '.'); ?> đ </strong></p>

                                <p>
                                    Số lượng: 
                                    <button class="btn btn-sm btn-outline-primary update-cart" 
                                            data-id="<?php echo $id; ?>" data-action="decrease">-</button>
                                    
                                    <input type="text" class="quantity-input text-center" id="quantity-<?php echo $id; ?>" 
                                           value="<?php echo htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?>" 
                                           size="2" readonly>

                                    <button class="btn btn-sm btn-outline-primary update-cart" 
                                            data-id="<?php echo $id; ?>" data-action="increase">+</button>
                                </p>
                            </div>

                            <button class="btn btn-danger btn-sm remove-cart" data-id="<?php echo $id; ?>">❌</button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <h3 class="text-right mt-3">Tổng tiền: <span id="total"><?php echo number_format($total, 0, ',', '.'); ?> đ</span></h3>

                <div class="text-center mt-3">
                    <a href="/Book" class="btn btn-secondary">Tiếp tục mua sắm</a>
                    <a href="/Book/checkout" class="btn btn-success">Thanh Toán</a>
                </div>
            </div>
        <?php else: ?>
            <p class="text-center text-danger">🛍️ Giỏ hàng của bạn đang trống.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    .cart-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background: linear-gradient(to bottom right, #d4faff, #b3ecff);
        padding: 20px;
    }

    .cart-content {
        width: 100%;
        max-width: 600px;
        background: white;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease-in-out;
    }

    .cart-content:hover {
        transform: scale(1.02);
    }

    .cart-item {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 10px;
        transition: background 0.3s ease-in-out;
    }

    .cart-item:hover {
        background: #f8f9fa;
    }

    .product-img {
        max-width: 80px;
        border-radius: 5px;
        margin-top: 5px;
    }

    .quantity-input {
        width: 50px;
        text-align: center;
        font-weight: bold;
        border: 2px solid #ddd;
        border-radius: 5px;
        margin: 0 5px;
    }

    .remove-cart {
        transition: background 0.3s ease-in-out;
    }

    .remove-cart:hover {
        background: red;
    }

    @media (max-width: 768px) {
        .cart-content {
            width: 90%;
        }
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Cập nhật số lượng sản phẩm
    $(".update-cart").click(function() {
        let product_id = $(this).data("id");
        let action = $(this).data("action");
        let input = $("#quantity-" + product_id);
        let newQuantity = parseInt(input.val());

        if (action === "increase") {
            newQuantity++;
        } else if (action === "decrease" && newQuantity > 1) {
            newQuantity--;
        }

        input.val(newQuantity);

        $.ajax({
            url: "/Book/updateCart", // Changed from Product/updateCart
            method: "POST",
            data: { product_id: product_id, quantity: newQuantity },
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    $("#total").text(response.total); // Cập nhật tổng tiền
                }
            }
        });
    });

    // Xóa sản phẩm khỏi giỏ hàng
    $(".remove-cart").click(function() {
        let product_id = $(this).data("id");
        let cartItem = $("#quantity-" + product_id).closest("li"); // Lấy phần tử cha để xóa

        if (confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            $.ajax({
                url: "/Book/removeFromCart", // Changed from Product/removeFromCart
                method: "POST",
                data: { product_id: product_id },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        cartItem.remove(); // Xóa sản phẩm khỏi giao diện
                        $("#total").text(response.total); // Cập nhật tổng tiền ngay
                    }
                }
            });
        }
    });
});

</script>