<?php

namespace App\Controllers;
// File: project/includes/controllers/CategoryController.php

require_once __DIR__ . '/../config.php';

use App\Models\CategoryModel;

class CategoryController
{
    private $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
    }

    public function listCategories()
    {
        return $this->categoryModel->getAllCategories();
    }

    public function showCategoryForm()
    {
        $category = null;
        if (isset($_GET['id'])) {
            $category = $this->categoryModel->findCategoryById(intval($_GET['id']));
        }
        return $category;
    }

    public function handleSaveCategory()
    {
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
        header("Location: " . BASE_URL . 'public/admin/categories.php');
        exit();
    }

    public function handleDeleteCategory()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $id = intval($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error_message'] = "❌ Lỗi: Không tìm thấy ID danh mục để xóa.";
        } else {
            // --- THÊM LOGIC KIỂM TRA SẢN PHẨM TRƯỚC KHI XÓA ---
            $productCount = $this->categoryModel->countProductsInCategory($id);

            // Vẫn thực hiện xóa vì ràng buộc DB là SET NULL, nhưng đưa ra cảnh báo trước
            if ($this->categoryModel->deleteCategory($id)) {

                if ($productCount > 0) {
                    // Cảnh báo nhưng vẫn thành công
                    $_SESSION['success_message'] = "✅ Xóa danh mục thành công! {$productCount} sản phẩm liên quan đã được gỡ bỏ danh mục.";
                } else {
                    $_SESSION['success_message'] = "✅ Xóa danh mục thành công!";
                }
            } else {
                $_SESSION['error_message'] = "❌ Lỗi: Xóa danh mục thất bại. (Có thể do lỗi hệ thống hoặc ràng buộc khác).";
            }
        }

        header("Location: " . BASE_URL . 'public/admin/categories.php');
        exit();
    }

    /**
     * Thêm: Xử lý tạo danh mục mới
     */
    public function handleCreateCategory()
    {
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

        header("Location: " . BASE_URL . 'public/admin/categories.php');
        exit();
    }

    /**
     * Thêm: Xử lý cập nhật danh mục
     */
    public function handleUpdateCategory()
    {
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

        header("Location: " . BASE_URL . 'public/admin/categories.php');
        exit();
    }
}
