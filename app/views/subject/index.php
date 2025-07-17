<?php
// No longer including header/footer here, it's done by admin_layout.php
ob_start(); // Start output buffering
?>

<div class="container-fluid admin-content-container py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h2 page-title">Quản lý Chủ đề / Ngành nghề</h1>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <a href="/Subject/create" class="btn btn-success btn-add-new">
                <i class="fas fa-plus-circle mr-2"></i> Thêm chủ đề/ngành nghề mới
            </a>
        <?php endif; ?>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_message); ?>
        </div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?= htmlspecialchars($error_message); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Tên chủ đề/ngành nghề</th>
                        <th>Mô tả</th>
                        <th class="text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Hàm đệ quy để hiển thị các hàng trong bảng
                    function displaySubjectTableRows($subjectsByParentArray, $parentId, $level = 0) {
                        if (!isset($subjectsByParentArray[$parentId])) {
                            return;
                        }
                        foreach ($subjectsByParentArray[$parentId] as $subject) {
                            $indent = str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", $level); // Thụt lề bằng khoảng trắng
                            echo '<tr>';
                            echo '<td style="text-align: left;"><span class="subject-indent">' . $indent . htmlspecialchars($subject->name) . '</span></td>';
                            echo '<td>' . htmlspecialchars($subject->description) . '</td>';
                            echo '<td class="text-center">';
                            echo '<a href="/Subject/view/' . $subject->id . '" class="btn btn-info btn-sm mx-1"><i class="fas fa-eye"></i> Xem</a>';
                            echo '<a href="/Subject/edit/' . $subject->id . '" class="btn btn-warning btn-sm mx-1"><i class="fas fa-edit"></i> Sửa</a>';
                            echo '<a href="/Subject/delete/' . $subject->id . '" class="btn btn-danger btn-sm mx-1" onclick="return confirm(\'Bạn có chắc chắn muốn xóa chủ đề này? Tất cả chủ đề con sẽ trở thành chủ đề cấp cao nhất.\');"><i class="fas fa-trash-alt"></i> Xóa</a>';
                            echo '</td>';
                            echo '</tr>';
                            displaySubjectTableRows($subjectsByParentArray, $subject->id, $level + 1); // Gọi đệ quy cho chủ đề con
                        }
                    }

                    // Hiển thị các chủ đề cấp cao nhất (parent_id là NULL hoặc 0)
                    displaySubjectTableRows($subjectsByParent, 0); // Đối với parent_id = 0
                    displaySubjectTableRows($subjectsByParent, null); // Đối với parent_id = NULL
                    
                    if (empty($subjectsByParent[0]) && empty($subjectsByParent[null])) :
                    ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">Chưa có chủ đề/ngành nghề nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$main_content = ob_get_clean(); // Get content and clear buffer
include 'app/views/shares/admin_layout.php'; // Include the new layout
?>

<style>
    /* Styling for Subject Index page within admin layout */
    .page-title {
        color: #34495e;
        font-weight: bold;
    }
    .btn-add-new {
        background-color: #2ecc71;
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
    .table th, .table td {
        vertical-align: middle;
    }
    .table thead th {
        background-color: #f8f9fc;
        color: #4e73df;
        font-weight: bold;
        border-bottom: 2px solid #e3e6f0;
    }
    .table-bordered th, .table-bordered td {
        border: 1px solid #e3e6f0;
    }
    .subject-indent {
        font-weight: bold; /* Make indented subjects stand out */
        padding-left: 10px; /* Base padding */
    }
    td .btn-sm {
        padding: 5px 10px;
        font-size: 0.8em;
        margin: 0 2px;
        border-radius: 4px;
    }
    .btn-info {
        background-color: #3498db;
        border-color: #3498db;
    }
    .btn-warning {
        background-color: #f39c12;
        border-color: #f39c12;
    }
    .btn-danger {
        background-color: #e74c3c;
        border-color: #e74c3c;
    }
</style>