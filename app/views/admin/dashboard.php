<!-- Thêm CDN của Chart.js vào đầu file hoặc trong header.php -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom">
    <h1 class="h2">Bảng điều khiển</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="/Book/add" class="btn btn-sm btn-primary me-2">
            <i class="fas fa-plus-circle me-1"></i>
            Thêm sách mới
        </a>
        <a href="/Report/view" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-file-download me-1"></i>
            Xuất báo cáo
        </a>
    </div>
</div>

<!-- 4 Thẻ số liệu quan trọng -->
<div class="row">
    <!-- Card 1: Tổng số sách (ĐÃ KHÔI PHỤC) -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-primary border-4 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-primary text-uppercase mb-1">Tổng số sách</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= htmlspecialchars($totalBooks ?? 0) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book-open fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 2: Thành viên -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-success border-4 shadow-sm">
            <div class="card-body">
                 <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-success text-uppercase mb-1">Thành viên</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= htmlspecialchars($activeMembers ?? 0) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 3: Đang được mượn -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-info border-4 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-info text-uppercase mb-1">Đang được mượn</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= htmlspecialchars($loansCount ?? 0) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card 4: Sách quá hạn -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 border-start border-danger border-4 shadow-sm">
            <div class="card-body">
                 <div class="row align-items-center">
                    <div class="col">
                        <div class="text-xs fw-bold text-danger text-uppercase mb-1">Sách quá hạn</div>
                        <div class="h5 mb-0 fw-bold text-gray-800"><?= htmlspecialchars($overdueCount ?? 0) ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Khu vực Biểu đồ và Sách phổ biến -->
<div class="row">
    <!-- Cột chính (Biểu đồ và Hoạt động gần đây) -->
    <div class="col-lg-8">
        <!-- Biểu đồ -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Thống kê Mượn/Trả (7 ngày qua)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 320px;">
                    <canvas id="loanReturnChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Hoạt động gần đây -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Hoạt động gần đây</h6>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Thành viên</th>
                            <th>Sách</th>
                            <th class="text-center">Hành động</th>
                            <th>Thời gian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($recentActivities)): ?>
                            <?php foreach($recentActivities as $activity): ?>
                                <tr>
                                    <td><?= htmlspecialchars($activity->member_name) ?></td>
                                    <td><?= htmlspecialchars($activity->book_title) ?></td>
                                    <td class="text-center">
                                        <?php if($activity->action === 'loan'): ?>
                                            <span class="badge bg-primary">Mượn</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Trả</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($activity->timestamp)) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center text-muted p-4">Không có hoạt động nào gần đây.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cột phụ (Sách phổ biến) -->
    <div class="col-lg-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 fw-bold text-primary">Sách được mượn nhiều nhất</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($popularBooks)): ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach($popularBooks as $book): ?>
                            <li class="list-group-item d-flex align-items-center px-0">
                                <img src="/<?= htmlspecialchars($book->image ?? 'uploads/default-book.jpg') ?>" alt="" class="rounded me-3" style="width: 40px; height: 60px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <a href="/Book/show/<?= $book->id ?>" class="text-decoration-none text-dark fw-bold d-block"><?= htmlspecialchars($book->name) ?></a>
                                    <small class="text-muted">Lượt mượn: <?= $book->loan_count ?></small>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <div class="text-center text-muted p-4">Chưa có dữ liệu về sách phổ biến.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script để vẽ biểu đồ -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lấy dữ liệu từ PHP
    const chartData = JSON.parse('<?= $chartDataJson ?? '{}' ?>');
    
    // Cấu hình biểu đồ
    const ctx = document.getElementById('loanReturnChart').getContext('2d');
    if (window.myLineChart) {
        window.myLineChart.destroy();
    }
    window.myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels || [],
            datasets: [{
                label: 'Sách mượn',
                data: chartData.loans || [],
                borderColor: 'rgba(78, 115, 223, 1)',
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                fill: true,
                tension: 0.3
            }, {
                label: 'Sách trả',
                data: chartData.returns || [],
                borderColor: 'rgba(28, 200, 138, 1)',
                backgroundColor: 'rgba(28, 200, 138, 0.05)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            }
        }
    });
});
</script>
