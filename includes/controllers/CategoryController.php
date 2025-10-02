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
        header('Location: ' . BASE_URL . 'public/admin/categories.php');
        exit();
    }

    public function handleDeleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $this->categoryModel->deleteCategory(intval($_POST['id']));
        }
        header('Location: ' . BASE_URL . 'public/admin/categories.php');
        exit();
    }
}
?>