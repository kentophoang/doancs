<?php
require_once('app/models/SubjectModel.php');
require_once('app/config/database.php');

$db = (new Database())->getConnection();
$subjectModel = new SubjectModel($db);
$subjects = $subjectModel->getSubjects();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Thư viện Thông minh</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f0f8ff 0%, #e0f2f7 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar {
            background: linear-gradient(to right, #4CAF50, #2E8B57);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: white !important;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .navbar-nav .nav-link:hover {
            color: #FFEB3B !important;
            transform: translateY(-2px);
        }
        .dropdown-menu {
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .dropdown-item:hover {
            background-color: #e8f5e9;
            color: #2E8B57;
        }
        .form-inline .form-control {
            border-radius: 20px;
            border: none;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
        }
        .form-inline .btn-outline-success {
            border-radius: 20px;
            background-color: #1976D2;
            border-color: #1976D2;
            color: white;
            padding: 8px 15px;
            margin-left: 5px;
            transition: background-color 0.3s ease;
        }
        .form-inline .btn-outline-success:hover {
            background-color: #1565C0;
            border-color: #1565C0;
        }
        .container.mt-4 {
            flex: 1;
        }
        .search-form-header {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 100%;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/">📚 Thư viện Thông minh</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/Book/">Danh sách sách</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="subjectDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Chủ đề / Ngành nghề
                        </a>
                        <div class="dropdown-menu" aria-labelledby="subjectDropdown">
                            <?php if (empty($subjects)) : ?>
                                <p class="dropdown-item text-muted">Chưa có chủ đề nào</p>
                            <?php else : ?>
                                <?php foreach ($subjects as $subject) : ?>
                                    <a class="dropdown-item" href="/Book?subject_id=<?= htmlspecialchars($subject->id) ?>">
                                        <?= htmlspecialchars($subject->name) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <div class="dropdown-divider"></div>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                                <a class="dropdown-item" href="/Subject/">Quản Lý Chủ đề</a>
                            <?php endif; ?>
                        </div>
                    </li>

                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Book/add">Thêm sách</a>
                        </li>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['username'])) : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/Book/myBorrowedBooks">Sách của tôi</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/account/profile">Hồ sơ của tôi</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item">
                        <form class="form-inline search-form-header" action="/Book" method="get">
                            <input class="form-control mr-sm-2" type="search" placeholder="Tìm sách, tác giả..." aria-label="Search" name="search">
                            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm</button>
                        </form>
                    </li>

                    <li class="nav-item">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link" href="/account/profile">Xin chào, <?= htmlspecialchars($_SESSION['username']) ?></a>
                        <?php else : ?>
                            <a class="nav-link" href="/account/login">Đăng nhập</a>
                        <?php endif; ?>
                    </li>

                    <li class="nav-item">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a class="nav-link" href="/account/logout">Đăng xuất</a>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>