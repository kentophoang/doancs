<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5" style="background: linear-gradient(to right, #d4fcf9, #eaf8fc); padding: 20px; border-radius: 10px;">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h2>Sửa sản phẩm</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();">
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                        <div class="form-group mb-3">
                            <label for="name">Tên sản phẩm:</label>
                            <input type="text" id="name" name="name" class="form-control fs-5" 
                                   value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Mô tả:</label>
                            <textarea id="description" name="description" class="form-control fs-5" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Giá:</label>
                            <input type="text" id="price" name="price" class="form-control fs-5 text-danger fw-bold" step="0.01" 
                                   value="<?php echo number_format($product->price, 0, ',', '.'); ?> đ" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_id">Danh mục:</label>
                            <select id="category_id" name="category_id" class="form-control fs-5" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" 
                                            <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image">Hình ảnh:</label>
                            <input type="file" id="image" name="image" class="form-control fs-5">
                            <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">
                            <?php if ($product->image): ?>
                                <div class="mt-2">
                                    <img src="/<?php echo $product->image; ?>" alt="Product Image" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-success w-100 fs-5">Lưu thay đổi</button>
                    </form>

                    <a href="/Product/list" class="btn btn-secondary w-100 mt-3 fs-5">Quay lại danh sách sản phẩm</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
