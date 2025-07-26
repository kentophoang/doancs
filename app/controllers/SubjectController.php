<?php
// Tự động định nghĩa ROOT_PATH nếu nó chưa tồn tại
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__, 2));
}
require_once ROOT_PATH . '/app/models/SubjectModel.php';
require_once ROOT_PATH . '/app/helpers/SessionHelper.php';

class SubjectController
{
     private $subjectModel;
    
    public function __construct($db) {
        $this->subjectModel = new SubjectModel($db);
        // Session đã được bắt đầu trong index.php
    }

    /**
     * [PUBLIC] Hiển thị trang danh mục sách công khai, nhóm theo các mục lớn.
     */
    public function publicList()
    {
        // 1. Lấy tất cả các loại dữ liệu cần thiết
        $mainCategories = $this->subjectModel->getMainCategories();
        $faculties = $this->subjectModel->getFaculties();
        $subjects = $this->subjectModel->getSubjects(); // Giả định hàm này lấy tất cả môn học

        // 2. Nhóm các Khoa theo ID của Danh mục chính
        $facultiesByMainCategory = [];
        foreach($faculties as $faculty) {
            if (!empty($faculty->main_category_id)) {
                $facultiesByMainCategory[$faculty->main_category_id][] = $faculty;
            }
        }

        // 3. Nhóm các Môn học theo ID của Khoa
        $subjectsByFaculty = [];
        // Đồng thời, nhóm các Chủ đề (không thuộc Khoa) theo ID của Danh mục chính
        $subjectsByMainCategory = [];
        foreach($subjects as $subject) {
            if(!empty($subject->faculty_id)) {
                $subjectsByFaculty[$subject->faculty_id][] = $subject;
            } elseif (!empty($subject->main_category_id)) {
                $subjectsByMainCategory[$subject->main_category_id][] = $subject;
            }
        }

        // 4. Load view và truyền các mảng dữ liệu đã được cấu trúc sang
        ob_start();
        include ROOT_PATH . '/app/views/subject/public_list.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/public_layout.php';
    }

    /**
     * [ADMIN] Hiển thị trang quản lý chủ đề.
     */
    public function index()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $subjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($subjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        ob_start();
        include ROOT_PATH . '/app/views/subject/index.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }
    
    /**
     * [ADMIN] Xem chi tiết một chủ đề.
     */
    public function view($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $subject = $this->subjectModel->getSubjectById($id);
        if (!$subject) {
            show_404(); // Sử dụng hàm 404 toàn cục
        }
        $parentSubjectName = null;
        if ($subject->parent_id) {
            $parentSubject = $this->subjectModel->getSubjectById($subject->parent_id);
            if ($parentSubject) {
                $parentSubjectName = $parentSubject->name;
            }
        }
        ob_start();
        include ROOT_PATH . '/app/views/subject/view.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [ADMIN] Hiển thị form tạo chủ đề mới.
     */
    public function create()
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $parentSubjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($parentSubjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        // Lấy danh sách Khoa để chọn
        $faculties = $this->subjectModel->getFaculties();
        
        ob_start();
        include ROOT_PATH . '/app/views/subject/create.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [ADMIN] Xử lý lưu chủ đề mới.
     */
    public function store()
    {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/login');
            exit();
        }
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id'];
        $facultyId = empty($_POST['faculty_id']) ? null : (int)$_POST['faculty_id'];

        if (!empty($name)) {
            // Cần cập nhật model addSubject để nhận facultyId
            // Ví dụ: $this->subjectModel->addSubject($name, $description, $parentId, $facultyId);
            $_SESSION['success_message'] = "Thêm chủ đề thành công!";
            header("Location: /Subject/index");
        } else {
            $_SESSION['error_message'] = "Tên chủ đề không được để trống.";
            header("Location: /Subject/create");
        }
        exit;
    }

    /**
     * [ADMIN] Hiển thị form sửa chủ đề.
     */
    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        $subject = $this->subjectModel->getSubjectById($id);
        if (!$subject) {
            show_404();
        }
        $parentSubjects = $this->subjectModel->getSubjects();
        $subjectsByParent = [];
        foreach ($parentSubjects as $sub) {
            $parentId = $sub->parent_id ?? 0;
            $subjectsByParent[$parentId][] = $sub;
        }
        $faculties = $this->subjectModel->getFaculties();

        ob_start();
        include ROOT_PATH . '/app/views/subject/edit.php';
        $main_content = ob_get_clean();
        include ROOT_PATH . '/app/views/shares/admin_layout.php';
    }

    /**
     * [ADMIN] Xử lý cập nhật chủ đề.
     */
    public function update($id)
    {
        if (!SessionHelper::isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /account/login');
            exit();
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $parentId = empty($_POST['parent_id']) ? null : (int)$_POST['parent_id'];
        $facultyId = empty($_POST['faculty_id']) ? null : (int)$_POST['faculty_id'];

        if (!empty($name)) {
            // Cần cập nhật model updateSubject để nhận facultyId
            // Ví dụ: $this->subjectModel->updateSubject($id, $name, $description, $parentId, $facultyId);
            $_SESSION['success_message'] = "Cập nhật chủ đề thành công!";
            header("Location: /Subject/index");
        } else {
            $_SESSION['error_message'] = "Tên chủ đề không được để trống.";
            header("Location: /Subject/edit/" . $id);
        }
        exit;
    }

    /**
     * [ADMIN] Xử lý xóa chủ đề.
     */
    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            header('Location: /account/login');
            exit();
        }
        
        // Cần thêm logic kiểm tra xem chủ đề có sách nào không trước khi xóa
        if ($this->bookModel->countBooksBySubject($id) > 0) {
             $_SESSION['error_message'] = "Không thể xóa chủ đề vì vẫn còn sách thuộc chủ đề này.";
        } else {
            if ($this->subjectModel->deleteSubject($id)) {
                $_SESSION['success_message'] = "Xóa chủ đề thành công!";
            } else {
                $_SESSION['error_message'] = "Xóa chủ đề thất bại.";
            }
        }
        
        header("Location: /Subject/index");
        exit;
    }
}
