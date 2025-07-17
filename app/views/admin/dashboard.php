<?php 
ob_start(); // Start output buffering
?>

            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Bảng điều khiển LIBSMART</h1>
            </div>

            <p class="mb-4 text-muted">Chào mừng trở lại! Đây là tình hình hoạt động của thư viện thông minh hôm nay.</p>

            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Tổng số sách</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($totalBooks) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-book fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Thành viên hoạt động</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($activeMembers) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Sách đang lưu hành</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($availableBooks) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Sách quá hạn</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($overdueBooks) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Tổng phí phạt</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($totalFines) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-blue shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-blue text-uppercase mb-1">Tổng trưởng thành</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?= htmlspecialchars($growthRate) ?></div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-dark-blue">Hoạt động gần đây</h6>
                            <a href="#" class="text-decoration-none text-info">Xem tất cả</a>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-arrow-circle-right text-success mr-2"></i> Nguyễn Văn An mượn "Tổ thầy hoa vàng trên cỏ xanh"
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-arrow-circle-left text-info mr-2"></i> Lê Minh Châu trả "Cách mạng công nghiệp 4.0"
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-user-plus text-primary mr-2"></i> Thành viên mới đăng ký: Phạm Thị Dung
                                </a>
                                <a href="#" class="list-group-item list-group-item-action">
                                    <i class="fas fa-exclamation-circle text-warning mr-2"></i> Nhắc nhở quá hạn gửi đến Trần Thị Bình
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-dark-blue">Sách phổ biến</h6>
                            <a href="#" class="text-decoration-none text-info">Xem tất cả</a>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                <?php if (!empty($popularBooks)): ?>
                                    <?php foreach ($popularBooks as $book): ?>
                                        <li class="media mb-3">
                                            <?php if ($book->image): ?>
                                                <img src="/<?= htmlspecialchars($book->image) ?>" class="mr-3 rounded" alt="Book Cover" style="width: 60px; height: 90px; object-fit: cover;">
                                            <?php else: ?>
                                                <img src="/uploads/default-book.jpg" class="mr-3 rounded" alt="Default Cover" style="width: 60px; height: 90px; object-fit: cover;">
                                            <?php endif; ?>
                                            <div class="media-body">
                                                <h5 class="mt-0 mb-1"><?= htmlspecialchars($book->name) ?></h5>
                                                <p class="text-muted mb-0">Tác giả: <?= htmlspecialchars($book->author) ?></p>
                                                <p class="text-muted mb-0">Chủ đề: <?= htmlspecialchars($book->subject_name) ?></p>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-center text-muted">Chưa có sách phổ biến nào.</p>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

<?php 
$main_content = ob_get_clean(); // Get content and clear buffer
include 'app/views/shares/admin_layout.php'; // Include the new layout
?>