<?php 
// No longer including header/footer here, it's done by admin_layout.php
ob_start(); // Start output buffering
?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h2 page-title">Quản lý bộ sưu tập sách của thư viện</h1>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/Book/add" class="btn btn-success btn-add-new">
                <i class="fas fa-plus-circle mr-2"></i> Thêm sách mới
            </a>
        <?php endif; ?>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="/Book" class="form-row align-items-center mb-3">
                <div class="col-md-3 mb-2 mb-md-0">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text search-icon-bg"><i class="fas fa-search text-muted"></i></span>
                        </div>
                        <input type="text" id="search" name="search" placeholder="Tìm kiếm sách, tác giả, ISBN..." class="form-control" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select id="subject_id" name="subject_id" class="form-control custom-select">
                        <option value="">Tất cả loại</option>
                        <?php
                        function renderSubjectOptionsFlat($subjectsArray, $selectedSubjectId) {
                            foreach ($subjectsArray as $subject) {
                                $selected = (isset($selectedSubjectId) && $selectedSubjectId == $subject->id) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($subject->id) . '" ' . $selected . '>' . htmlspecialchars($subject->name) . '</option>';
                            }
                        }
                        if (isset($subjects) && is_array($subjects)) {
                           renderSubjectOptionsFlat($subjects, $_GET['subject_id'] ?? null);
                        }
                        ?>
                    </select>
                </div>

                <div class="col-md-2 mb-2 mb-md-0">
                    <select id="sort" name="sort" class="form-control custom-select">
                        <option value="">Sắp xếp theo</option>
                        <option value="newest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'newest') ? 'selected' : '' ?>>Năm mới nhất</option>
                        <option value="oldest" <?= (isset($_GET['sort']) && $_GET['sort'] == 'oldest') ? 'selected' : '' ?>>Năm cũ nhất</option>
                        <option value="name_asc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_asc') ? 'selected' : '' ?>>Tên sách (A-Z)</option>
                        <option value="name_desc" <?= (isset($_GET['sort']) && $_GET['sort'] == 'name_desc') ? 'selected' : '' ?>>Tên sách (Z-A)</option>
                    </select>
                </div>
                
                <div class="col-md-2 mb-2 mb-md-0">
                    <label for="min_year" class="mr-2 mb-0 d-md-none">Năm XB:</label>
                    <div class="d-flex">
                        <input type="number" id="min_year" name="min_year" placeholder="Từ năm" class="form-control form-control-sm w-auto mr-1" value="<?= htmlspecialchars($_GET['min_year'] ?? '') ?>">
                        <label for="max_year" class="mr-2 mb-0">-</label>
                        <input type="number" id="max_year" name="max_year" placeholder="Đến năm" class="form-control form-control-sm w-auto mr-1" value="<?= htmlspecialchars($_GET['max_year'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary btn-block filter-btn">Lọc</button>
                </div>
            </form>
            <div class="text-right mt-2">
                <button class="btn btn-outline-info btn-advanced-filter">
                    <i class="fas fa-filter mr-1"></i> Bộ lọc nâng cao
                </button>
            </div>
        </div>
    </div>


    <div class="row book-grid">
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-md-3 mb-4">
                    <div class="card book-card h-100 shadow-sm">
                        <?php if ($book->image): ?>
                            <img src="/<?php echo htmlspecialchars($book->image); ?>" class="card-img-top book-img" alt="Book Image">
                        <?php else: ?>
                            <img src="/uploads/default-book.jpg" class="card-img-top book-img" alt="Default Book Image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-1">
                                <a href="/Book/show/<?php echo $book->id; ?>" class="text-decoration-none text-dark font-weight-bold">
                                    <?php echo htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted mb-1"><small><strong>Tác giả:</strong> <?php echo htmlspecialchars($book->author, ENT_QUOTES, 'UTF-8'); ?></small></p>
                            <p class="card-text text-muted mb-1"><small><strong>Năm XB:</strong> <?php echo htmlspecialchars($book->publication_year, ENT_QUOTES, 'UTF-8'); ?></small></p>
                            <p class="card-text text-muted mb-1"><small><strong>ISBN:</strong> <?php echo htmlspecialchars($book->ISBN, ENT_QUOTES, 'UTF-8'); ?></small></p>
                            <p class="card-text text-muted flex-grow-1"><small><strong>Mô tả:</strong> <?php echo htmlspecialchars(mb_substr($book->description, 0, 70), ENT_QUOTES, 'UTF-8'); ?>...</small></p>
                            <p class="card-text mb-1"><small><strong>Chủ đề:</strong> <span class="badge badge-secondary"><?php echo htmlspecialchars($book->subject_name, ENT_QUOTES, 'UTF-8'); ?></span></small></p>
                            <p class="card-text mb-2"><small><strong>Hiện có:</strong> <span class="badge badge-success"><?php echo htmlspecialchars($book->available_copies, ENT_QUOTES, 'UTF-8'); ?></span> / <?php echo htmlspecialchars($book->number_of_copies, ENT_QUOTES, 'UTF-8'); ?></small></p>
                        </div>
                        <div class="card-footer text-center bg-transparent border-top-0">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="/Book/show/<?php echo $book->id; ?>" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> Xem
                                </a>
                                <a href="/Book/edit/<?php echo $book->id; ?>" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Sửa
                                </a>
                                <a href="/Book/delete/<?php echo $book->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?');">
                                    <i class="fas fa-trash-alt"></i> Xóa
                                </a>
                            <?php endif; ?>
                            <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
                                <?php if (isset($_SESSION['username'])): ?>
                                    <?php if ($book->available_copies > 0): ?>
                                        <a href="/Book/borrow/<?php echo $book->id; ?>" class="btn btn-primary btn-sm mt-2">Mượn sách</a>
                                    <?php else: ?>
                                        <button class="btn btn-secondary btn-sm mt-2" disabled>Hết sách</button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <p class="text-muted mt-2 mb-0" style="font-size: 0.85em;">Đăng nhập để mượn</p>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p class="text-center text-muted">Không có sách trong chủ đề này hoặc theo tiêu chí tìm kiếm của bạn.</p>
            </div>
        <?php endif; ?>
    </div>

<?php 
$main_content = ob_get_clean(); // Get content and clear buffer
include 'app/views/shares/admin_layout.php'; // Include the new layout
?>

<style>
    /* Moved from original book/list.php, adapted for admin layout */
    .admin-content-container {
        /* This class is now defined in admin_layout.php's main tag */
        /* padding-left: 20px; Adjust for sidebar - now handled by main's ml-sm-auto */
    }
    .page-title {
        color: #34495e; /* Dark blue-gray for titles */
        font-weight: bold;
    }
    .btn-add-new {
        background-color: #2ecc71; /* Green for add new button */
        border-color: #2ecc71;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 5px;
    }
    .btn-add-new:hover {
        background-color: #27ae60;
        border-color: #27ae60;
    }
    .card.shadow {
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15) !important;
    }
    .form-control, .form-control-sm, .custom-select {
        border-radius: 5px;
        border: 1px solid #e2e6ea; /* Light gray border for form elements */
        box-shadow: none;
    }
    .input-group-text {
        background-color: #f8f9fa; /* Light background for search icon */
        border: 1px solid #e2e6ea;
        border-right: none;
        border-radius: 5px 0 0 5px;
    }
    .filter-btn {
        background-color: #4e73df; /* Blue for filter button */
        border-color: #4e73df;
        border-radius: 5px;
    }
    .filter-btn:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
    }
    .btn-advanced-filter {
        border-radius: 5px;
        color: #3498db;
        border-color: #3498db;
        background-color: white;
    }
    .btn-advanced-filter:hover {
        background-color: #3498db;
        color: white;
    }

    .book-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        background-color: white; /* White background for cards */
    }
    .book-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    .book-card .book-img {
        height: 220px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
        width: 100%;
    }
    .card-body {
        padding: 15px;
        display: flex;
        flex-direction: column;
    }
    .card-title a {
        font-size: 1.05em;
        font-weight: bold;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .card-text {
        font-size: 0.85em;
        color: #555;
        margin-bottom: 3px;
    }
    .card-footer {
        background: #f8f9fa;
        padding: 10px 15px;
        border-top: 1px solid #eee;
    }
    .btn-info {
        background-color: #3498db; /* Blue for 'Xem' */
        border-color: #3498db;
    }
    .btn-warning {
        background-color: #f39c12; /* Orange for 'Sửa' */
        border-color: #f39c12;
    }
    .btn-danger {
        background-color: #e74c3c; /* Red for 'Xóa' */
        border-color: #e74c3c;
    }
    .btn-info, .btn-warning, .btn-danger { /* Consolidated admin action button styles */
        font-size: 0.8em;
        padding: 5px 8px;
        margin: 2px;
        color: white; /* Ensure text is white */
    }
    .btn-primary, .btn-secondary { /* Public view buttons */
        font-size: 0.85em;
        padding: 6px 10px;
        margin: 2px;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
    }
    .badge-secondary {
        background-color: #6c757d;
        color: white;
    }
    .text-custom {
        color: #333;
    }
    .form-row {
        justify-content: flex-start;
    }
    .form-row .col-md-3, .form-row .col-md-2, .form-row .col-md-1 {
        flex: 0 0 auto;
        padding-right: 10px;
    }
    .form-row .col-md-1:last-child {
        padding-right: 0;
    }
</style>