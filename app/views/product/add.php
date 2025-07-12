<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">➕ Thêm sản phẩm mới</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-lg p-4 form-container">
        <form method="POST" action="/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="mb-3">
                <label for="name" class="form-label">📌 Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">📝 Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="3" placeholder="Nhập mô tả sản phẩm" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">💰 Giá:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" placeholder="Nhập giá sản phẩm" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">📂 Danh mục:</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <option value="" disabled selected>Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">🖼️ Hình ảnh:</label>
                <input type="file" id="image" name="image" class="form-control">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary w-50">➕ Thêm sản phẩm</button>
                <a href="/Product/list" class="btn btn-secondary w-50 ms-2">⬅️ Quay lại</a>
            </div>
        </form>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<style>
    /* Nền gradient nhẹ */
    body {
        background: linear-gradient(to bottom right, #d4faff, #b3ecff);
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background: white;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease-in-out;
    }

    /* Hiệu ứng hover */
    .form-container:hover {
        transform: scale(1.02);
    }

    /* Căn chỉnh input */
    .form-control, .form-select {
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 10px;
        font-size: 16px;
    }

    /* Nút bấm */
    .btn-primary {
        background: #007bff;
        border: none;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background: #0056b3;
    }

    .btn-secondary {
        background: #6c757d;
        border: none;
        font-size: 16px;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-secondary:hover {
        background: #545b62;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-container {
            width: 90%;
        }

        .btn {
            font-size: 14px;
        }
    }
</style>

<script>
function validateForm() {
    let name = document.getElementById("name").value.trim();
    let description = document.getElementById("description").value.trim();
    let price = document.getElementById("price").value.trim();
    let category = document.getElementById("category_id").value;

    if (name === "" || description === "" || price === "" || category === "") {
        alert("Vui lòng điền đầy đủ thông tin!");
        return false;
    }

    if (price <= 0) {
        alert("Giá sản phẩm phải lớn hơn 0!");
        return false;
    }

    return true;
}
</script>
