<?php include 'app/views/shares/header.php'; ?>

<div class="container py-1" style="margin-top: -30px;">

    <?php
    $subjectParam = isset($_GET['subject_id']) ? '&subject_id=' . $_GET['subject_id'] : '';
    $subjectName = "Danh sách sách";
    if (isset($_GET['subject_id'])) {
        // Trong View, để truy cập subjectModel, bạn cần đảm bảo nó được truyền từ Controller.
        // Hoặc khởi tạo tạm thời (ít khuyến khích cho production)
        $db = (new Database())->getConnection(); // Database class from app/database/Database.php
        $tempSubjectModel = new SubjectModel($db); // SubjectModel class from app/models/SubjectModel.php
        $subject = $tempSubjectModel->getSubjectById($_GET['subject_id']);
        if ($subject) {
            $subjectName = "Sách về: " . htmlspecialchars($subject->name);
        }
    }
    ?>

    <h1 class="text-center mb-3"><?= $subjectName ?></h1>

    <div class="row mb-3 align-items-center">
        <div class="col-md-6 d-flex align-items-center">
            <h6 class="text-custom mr-2" style="font-weight: bold;">Sắp xếp theo:</h6>
            <div class="sort-buttons d-flex align-items-center">
                <a href="?sort=newest<?= $subjectParam ?>" class="btn btn-light btn-sm mr-2" style="border: 1px solid #ccc; border-radius: 5px;">Năm mới nhất</a>
                <a href="?sort=oldest<?= $subjectParam ?>" class="btn btn-light btn-sm" style="border: 1px solid #ccc; border-radius: 5px;">Năm cũ nhất</a>
            </div>
        </div>

        <div class="col-md-6 text-right">
            <form method="GET" action="/Book" class="d-flex align-items-center justify-content-end">
                <?php if (isset($_GET['subject_id'])): ?>
                    <input type="hidden" name="subject_id" value="<?= htmlspecialchars($_GET['subject_id']) ?>">
                <?php endif; ?>
                <label for="min_year" class="mr-2 mb-0">Năm XB:</label>
                <input type="number" id="min_year" name="min_year" placeholder="Từ năm" class="form-control form-control-sm w-auto mr-1" value="<?= htmlspecialchars($_GET['min_year'] ?? '') ?>">
                <label for="max_year" class="mr-2 mb-0">-</label>
                <input type="number" id="max_year" name="max_year" placeholder="Đến năm" class="form-control form-control-sm w-auto mr-1" value="<?= htmlspecialchars($_GET['max_year'] ?? '') ?>">
                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 5px;">Lọc</button>
            </form>
        </div>
    </div>


    <div class="row">
        <?php if (!empty($books)): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-md-3 mb-4">
                    <div class="card book-card h-100">
                        <?php if ($book->image): ?>
                            <img src="/<?php echo $book->image; ?>" class="card-img-top" alt="Book Image">
                        <?php else: ?>
                            <img src="/uploads/default-book.jpg" class="card-img-top" alt="Default Book Image">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/Book/show/<?php echo $book->id; ?>" class="text-decoration-none">
                                    <?php echo htmlspecialchars($book->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>
                            <p class="card-text"><strong>Tác giả:</strong> <?php echo htmlspecialchars($book->author, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text"><strong>Năm XB:</strong> <?php echo htmlspecialchars($book->publication_year, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text"><strong>Mô tả:</strong> <?php echo htmlspecialchars(mb_substr($book->description, 0, 70), ENT_QUOTES, 'UTF-8'); ?>...</p>
                            <p class="card-text"><strong>Chủ đề:</strong> <?php echo htmlspecialchars($book->subject_name, ENT_QUOTES, 'UTF-8'); ?></p>
                            <p class="card-text"><strong>Hiện có:</strong> <span class="badge badge-success"><?php echo htmlspecialchars($book->available_copies, ENT_QUOTES, 'UTF-8'); ?></span> / <?php echo htmlspecialchars($book->number_of_copies, ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                        <div class="card-footer text-center">
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <a href="/Book/show/<?php echo $book->id; ?>" class="btn btn-info btn-sm">Xem</a>
                                <a href="/Book/edit/<?php echo $book->id; ?>" class="btn btn-warning btn-sm">Sửa</a>
                                <a href="/Book/delete/<?php echo $book->id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?');">Xóa</a>
                            <?php endif; ?>
                            <?php if (isset($_SESSION['username'])): ?>
                                <?php if ($book->available_copies > 0): ?>
                                    <a href="/Book/borrow/<?php echo $book->id; ?>" class="btn btn-primary btn-sm mt-2">Mượn sách</a>
                                <?php else: ?>
                                    <button class="btn btn-secondary btn-sm mt-2" disabled>Hết sách</button>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted mt-2 mb-0" style="font-size: 0.85em;">Đăng nhập để mượn</p>
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
</div>

<style>
    body {
        background: linear-gradient(to right, #d4fcf7, #a0c4ff);
        font-family: 'Arial', sans-serif;
    }
    .book-card {
        transition: transform 0.3s ease-in-out;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    .book-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-bottom: 1px solid #eee;
    }
    .card-body {
        padding: 15px;
    }
    .card-title a {
        font-size: 1.1em;
        font-weight: bold;
        color: #333;
    }
    .card-text {
        font-size: 0.9em;
        color: #555;
        margin-bottom: 5px;
    }
    .card-footer {
        background: #f8f9fa;
        padding: 10px 15px;
        border-top: 1px solid #eee;
    }
    .btn-info, .btn-warning, .btn-danger, .btn-primary, .btn-secondary {
        font-size: 0.85em;
        padding: 6px 10px;
        margin: 2px;
    }
    .badge-success {
        background-color: #28a745;
        color: white;
    }

    .text-custom {
        color: #333;
    }
    .sort-buttons .btn-light {
        background-color: #f0f0f0;
        color: #333;
        border: 1px solid #ccc;
    }
    .sort-buttons .btn-light:hover {
        background-color: #e0e0e0;
    }
    .form-control-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 5px;
    }
</style>
<?php include 'app/views/shares/footer.php'; ?>