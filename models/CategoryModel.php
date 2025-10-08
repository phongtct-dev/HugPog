<?php
// File: project/models/CategoryModel.php
require_once __DIR__ . '/../includes/db_connect.php';

class CategoryModel {
    public function getAllCategories() {
        $conn = db_connect();
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function findCategoryById($id) {
        $conn = db_connect();
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function createCategory($name, $description) {
        $conn = db_connect();
        $sql = "INSERT INTO categories (name, description) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $description);
        return $stmt->execute();
    }

    public function updateCategory($id, $name, $description) {
        $conn = db_connect();
        $sql = "UPDATE categories SET name = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $name, $description, $id);
        return $stmt->execute();
    }
    public function deleteCategory($id) {
        $conn = db_connect();
        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>