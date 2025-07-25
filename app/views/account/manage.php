    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h2 page-title">Quản lý thành viên</h1>
            <p class="text-muted mb-0">Quản lý thành viên và tài khoản của họ</p>
        </div>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/account/register" class="btn btn-success btn-add-new">
                <i class="fas fa-plus-circle mr-2"></i> Thêm thành viên mới
            </a>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/Account/manage" class="form-row align-items-center mb-3">
                <div class="col-md-3 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-icon-bg"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="search" name="search" placeholder="Tìm kiếm thành viên..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select id="status" name="status" class="form-control custom-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" <?= (isset($_GET['status']) && $_GET['status'] == 'active') ? 'selected' : '' ?>>Hoạt động</option>
                        <option value="inactive" <?= (isset($_GET['status']) && $_GET['status'] == 'inactive') ? 'selected' : '' ?>>Không hoạt động</option>
                        </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select id="sort" name="sort" class="form-control custom-select">
                        <option value="">Sắp xếp theo</option>
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Tên (A-Z)</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Tên (Z-A)</option>
                        </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block filter-btn">Lọc</button>
                </div>
                <div class="col-md-3 text-right">
                    <button class="btn btn-outline-info btn-advanced-filter">
                        <i class="fas fa-filter mr-1"></i> Bộ lọc nâng cao
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row member-grid">
        <?php if (!empty($accounts)): ?>
            <?php foreach ($accounts as $account): ?>
                <div class="col-md-4 mb-4">
                    <div class="card member-card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column align-items-center">
                            <div class="member-avatar-container d-flex justify-content-center align-items-center mb-3">
                                <div class="member-avatar rounded-circle d-flex justify-content-center align-items-center mr-3"
                                    style="background-color: <?= '#'.substr(md5($account->id), 0, 6); ?>; color: white;">
                                    <?= strtoupper(substr(htmlspecialchars($account->fullname), 0, 2)) ?>
                                </div>
                                <span class="member-status-badge badge badge-success">Hoạt động</span> </div>
                            
                            <h5 class="card-title text-dark font-weight-bold mb-1">
                                <?= htmlspecialchars($account->fullname) ?> <small class="text-muted">#<?= htmlspecialchars($account->id) ?></small>
                            </h5>
                            <p class="card-text text-muted mb-1"><small><i class="fas fa-envelope mr-1"></i> <?= htmlspecialchars($account->username) ?>@email.com</small></p>
                            <p class="card-text text-muted mb-3"><small><i class="fas fa-phone mr-1"></i> +84 901 234 567</small></p>
                            
                            <div class="d-flex justify-content-around w-100 mb-3 stat-section">
                                <div class="text-center">
                                    <h6 class="mb-0 text-primary">0</h6>
                                    <small class="text-muted">Đang mượn</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-0 text-danger">0</h6>
                                    <small class="text-muted">Quá hạn</small>
                                </div>
                                <div class="text-center">
                                    <h6 class="mb-0 text-warning">0 VND</h6>
                                    <small class="text-muted">Phí phạt</small>
                                </div>
                            </div>
                            
                            <p class="card-text text-muted mb-3"><small>hoạt động hơn 1 năm trước</small></p> <div class="text-center mt-auto action-buttons">
                                <a href="/account/view/<?= $account->id ?>" class="btn btn-info btn-sm action-btn">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="/account/edit/<?= $account->id ?>" class="btn btn-warning btn-sm action-btn">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/account/delete/<?= $account->id ?>" class="btn btn-danger btn-sm action-btn" onclick="return confirm('Bạn có chắc chắn muốn xóa thành viên này?');">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">Không tìm thấy thành viên nào theo tiêu chí tìm kiếm của bạn.</p>
            </div>
        <?php endif; ?>
    </div>

