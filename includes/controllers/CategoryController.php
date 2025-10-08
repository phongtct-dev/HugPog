<?php
// File: project/includes/controllers/CategoryController.php
require_once __DIR__ . '/../../models/CategoryModel.php';
require_once __DIR__ . '/../config.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    public function listCategories() {
        return $this->categoryModel->getAllCategories();
    }
    
    public function showCategoryForm() {
        $category = null;
        if (isset($_GET['id'])) {
            $category = $this->categoryModel->findCategoryById(intval($_GET['id']));
        }
        return $category;
    }

    public function handleSaveCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $id = $_POST['id'] ?? null;

            if ($id) {
                $this->categoryModel->updateCategory($id, $name, $description);
            } else {
                $this->categoryModel->createCategory($name, $description);
            }
        }
        header("Location: ". BASE_URL . 'public/admin/categories.php');
        exit();
    }

    public function handleDeleteCategory() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error_message'] = "❌ Lỗi: Không tìm thấy ID danh mục để xóa.";
        } 
        // LƯU Ý: Nếu danh mục có chứa sản phẩm, bạn nên kiểm tra điều kiện này trước khi xóa cứng
        elseif ($this->categoryModel->deleteCategory($id)) {
            $_SESSION['success_message'] = "✅ Xóa danh mục thành công!";
        } else {
            $_SESSION['error_message'] = "❌ Lỗi: Xóa danh mục thất bại.";
        }

        header("Location: ". BASE_URL . 'public/admin/categories.php');
        exit();
    }

    /**
     * Thêm: Xử lý tạo danh mục mới
     */
    public function handleCreateCategory() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'active'); // Mặc định là 'active'

        if (empty($name)) {
            $_SESSION['error_message'] = "❌ Lỗi: Tên danh mục không được để trống.";
        } elseif ($this->categoryModel->createCategory($name, $description, $status)) {
            $_SESSION['success_message'] = "✅ Tạo danh mục thành công!";
        } else {
            $_SESSION['error_message'] = "❌ Lỗi: Tạo danh mục thất bại.";
        }

        header("Location: ". BASE_URL . 'public/admin/categories.php');
        exit();
    }

    /**
     * Thêm: Xử lý cập nhật danh mục
     */
    public function handleUpdateCategory() {
        if (session_status() == PHP_SESSION_NONE) session_start();
        
        $id = intval($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $status = trim($_POST['status'] ?? 'active');

        if ($id <= 0 || empty($name)) {
            $_SESSION['error_message'] = "❌ Lỗi: Dữ liệu cập nhật không hợp lệ.";
        } elseif ($this->categoryModel->updateCategory($id, $name, $description, $status)) {
            $_SESSION['success_message'] = "✅ Cập nhật danh mục thành công!";
        } else {
            $_SESSION['error_message'] = "❌ Lỗi: Cập nhật danh mục thất bại.";
        }

        header("Location: ". BASE_URL . 'public/admin/categories.php');
        exit();
    }

}
?>