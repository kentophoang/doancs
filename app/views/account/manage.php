<?php
require_once 'app/helpers/SessionHelper.php';
?>

<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Quản lý Thành viên</h1>
    <a href="/account/register" class="btn btn-primary">
        <i class="fas fa-user-plus me-1"></i> Thêm thành viên mới
    </a>
</div>

<!-- Form tìm kiếm và lọc -->
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/Account/manage" class="row g-3 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="text" id="search" name="search" placeholder="Tìm theo tên, username, hoặc email..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select id="status" name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="verified" <?= (($_GET['status'] ?? '') == 'verified') ? 'selected' : '' ?>>Đã xác thực</option>
                    <option value="unverified" <?= (($_GET['status'] ?? '') == 'unverified') ? 'selected' : '' ?>>Chưa xác thực</option>
                </select>
            </div>
            <div class="col-md-3">
                <select id="sort" name="sort" class="form-select">
                    <option value="">Sắp xếp theo</option>
                    <option value="name_asc" <?= (($_GET['sort'] ?? '') == 'name_asc') ? 'selected' : '' ?>>Tên (A-Z)</option>
                    <option value="name_desc" <?= (($_GET['sort'] ?? '') == 'name_desc') ? 'selected' : '' ?>>Tên (Z-A)</option>
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-outline-primary w-100">Lọc</button>
            </div>
        </form>
    </div>
</div>

<!-- Bảng hiển thị danh sách thành viên -->
<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th scope="col">Thành viên</th>
                    <th scope="col">Vai trò</th>
                    <th scope="col" class="text-center">Trạng thái</th>
                    <th scope="col">Ngày tham gia</th>
                    <th scope="col" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($accounts)): ?>
                    <?php foreach ($accounts as $account): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3">
                                        <?= strtoupper(substr(htmlspecialchars($account->fullname), 0, 1)) ?>
                                    </div>
                                    <div>
                                        <a href="/account/view/<?= $account->id ?>" class="fw-bold text-dark text-decoration-none">
                                            <?= htmlspecialchars($account->fullname) ?>
                                        </a>
                                        <small class="d-block text-muted"><?= htmlspecialchars($account->email ?? $account->username) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if ($account->role === 'admin'): ?>
                                    <span class="badge bg-danger">Quản trị viên</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Thành viên</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($account->is_verified): ?>
                                    <span class="badge bg-success">Đã xác thực</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">Chưa xác thực</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php 
                                    // Kiểm tra xem cột created_at có tồn tại không
                                    if (isset($account->created_at)) {
                                        echo date('d/m/Y', strtotime($account->created_at));
                                    } else {
                                        echo 'N/A';
                                    }
                                ?>
                            </td>
                            <td class="text-center">
                                <a href="/account/view/<?= $account->id; ?>" class="btn btn-sm btn-outline-info" title="Xem chi tiết"><i class="fas fa-eye"></i></a>
                                <a href="/account/edit/<?= $account->id; ?>" class="btn btn-sm btn-outline-warning" title="Sửa vai trò"><i class="fas fa-user-shield"></i></a>
                                <a href="/account/delete/<?= $account->id; ?>" class="btn btn-sm btn-outline-danger" title="Xóa thành viên" onclick="return confirm('Bạn có chắc chắn muốn xóa thành viên này?');">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted p-5">
                            Không tìm thấy thành viên nào.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- CSS cho avatar -->
<style>
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #0d6efd;
        color: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 1.1rem;
    }
</style>
