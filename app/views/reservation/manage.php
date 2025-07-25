<?php
// app/views/reservation/manage.php
ob_start(); // Start output buffering
?>
<div class="container-fluid admin-content-container py-3">
    <h1 class="h2 page-title">Quản lý Đặt trước Sách</h1>
    <p class="text-muted">Đây là trang quản lý các yêu cầu đặt trước sách từ thành viên.</p>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/Reservation/manage" class="form-row align-items-center mb-3">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-icon-bg"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="search" name="search" placeholder="Tìm kiếm sách, thành viên, ISBN..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3 mb-2 mb-md-0">
                    <select id="status" name="status" class="form-control custom-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="pending" <?= (isset($_GET['status']) && $_GET['status'] == 'pending') ? 'selected' : '' ?>>Đang chờ</option>
                        <option value="ready" <?= (isset($_GET['status']) && $_GET['status'] == 'ready') ? 'selected' : '' ?>>Sẵn sàng</option>
                        <option value="cancelled" <?= (isset($_GET['status']) && $_GET['status'] == 'cancelled') ? 'selected' : '' ?>>Đã hủy</option>
                        <option value="fulfilled" <?= (isset($_GET['status']) && $_GET['status'] == 'fulfilled') ? 'selected' : '' ?>>Đã hoàn thành</option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-block filter-btn">Lọc</button>
                </div>
            </form>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="alert alert-success mt-3">
                    <?= htmlspecialchars($_SESSION['success_message']); unset($_SESSION['success_message']); ?>
                </div>
            <?php elseif (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger mt-3">
                    <?= htmlspecialchars($_SESSION['error_message']); unset($_SESSION['error_message']); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($reservations)): ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mt-3">
                        <thead class="thead-light">
                            <tr>
                                <th>ID Đặt trước</th>
                                <th>Tên sách</th>
                                <th>Người đặt</th>
                                <th>Ngày đặt</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reservations as $reservation): ?>
                                <tr>
                                    <td><?= htmlspecialchars($reservation->id) ?></td>
                                    <td><?= htmlspecialchars($reservation->book_name) ?></td>
                                    <td><?= htmlspecialchars($reservation->user_fullname) ?> (<?= htmlspecialchars($reservation->user_username) ?>)</td>
                                    <td><?= htmlspecialchars($reservation->reservation_date) ?></td>
                                    <td>
                                        <span class="badge 
                                            <?= ($reservation->status == 'pending') ? 'badge-warning' : 
                                               (($reservation->status == 'ready') ? 'badge-info' : 
                                               (($reservation->status == 'fulfilled') ? 'badge-success' : 'badge-secondary')) ?>">
                                            <?= htmlspecialchars($reservation->status) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($reservation->status == 'pending'): ?>
                                            <a href="/Reservation/approve/<?= $reservation->id ?>" class="btn btn-success btn-sm mx-1" onclick="return confirm('Duyệt yêu cầu đặt trước này?');">
                                                <i class="fas fa-check-circle"></i> Duyệt
                                            </a>
                                            <a href="/Reservation/cancel/<?= $reservation->id ?>" class="btn btn-warning btn-sm mx-1" onclick="return confirm('Hủy yêu cầu đặt trước này?');">
                                                <i class="fas fa-times-circle"></i> Hủy
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($reservation->status == 'ready' || $reservation->status == 'fulfilled' || $reservation->status == 'cancelled'): ?>
                                             <a href="/Reservation/delete/<?= $reservation->id ?>" class="btn btn-danger btn-sm mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa yêu cầu này?');">
                                                <i class="fas fa-trash-alt"></i> Xóa
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info text-center mt-3">
                    Chưa có yêu cầu đặt trước sách nào theo tiêu chí tìm kiếm.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php 
$main_content = ob_get_clean();
include 'app/views/shares/admin_layout.php';
?>