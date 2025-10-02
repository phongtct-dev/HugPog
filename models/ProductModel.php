<?php
// File: project/models/ProductModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class ProductModel {
    /**
     * Lấy tất cả sản phẩm đang ở trạng thái 'active'.
     * @return array Mảng chứa danh sách các sản phẩm.
     */
    public function getAllActiveProducts() {
        $conn = db_connect();
        $sql = "SELECT p.*, c.name AS category_name, 
                       pd.discount_percent, 
                       (p.price * (1 - pd.discount_percent / 100)) as discounted_price
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
                WHERE p.status = 'active'
                ORDER BY p.created_at DESC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //

    /**
     * Tìm một sản phẩm duy nhất dựa vào ID.
     * @param int $id ID của sản phẩm cần tìm.
     * @return array|null Mảng chứa thông tin sản phẩm, hoặc null nếu không tìm thấy.
     */
    public function findProductById($id) {
        $conn = db_connect();
        $sql = "SELECT p.*, c.name AS category_name, 
                       pd.discount_percent,
                       (p.price * (1 - pd.discount_percent / 100)) as discounted_price
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
                WHERE p.id = ? AND p.status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    //

    /**
     * Lấy TẤT CẢ sản phẩm cho trang admin (bao gồm cả active và inactive).
     * @return array
     */
    public function getAllProductsForAdmin() {
        $conn = db_connect();
        $sql = "SELECT p.*, c.name AS category_name 
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.id DESC";
        $result = $conn->query($sql);
        $products = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close();
        return $products;
    }

    /**
     * Tạo một sản phẩm mới.
     * @param array $data Mảng chứa dữ liệu sản phẩm từ form.
     * @return bool
     */
    public function createProduct($data) {
        $conn = db_connect();
        $sql = "INSERT INTO products (name, brand, description, price, stock, category_id, image_url) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssdiis",
            $data['name'],
            $data['brand'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'],
            $data['image_url']
        );
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    /**
     * Cập nhật thông tin một sản phẩm.
     * @param int $id ID sản phẩm.
     * @param array $data Dữ liệu cần cập nhật.
     * @return bool
     */
    public function updateProduct($id, $data) {
        $conn = db_connect();
        $sql = "UPDATE products SET 
                    name = ?, brand = ?, description = ?, price = ?, 
                    stock = ?, category_id = ?, image_url = ?, status = ?
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssdiissi",
            $data['name'],
            $data['brand'],
            $data['description'],
            $data['price'],
            $data['stock'],
            $data['category_id'],
            $data['image_url'],
            $data['status'],
            $id
        );
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    //

    /**
     * Tìm kiếm sản phẩm theo từ khóa.
     * @param string $keyword
     * @return array
     */
    public function searchProducts($keyword) {
        $conn = db_connect();
        $searchTerm = '%' . $keyword . '%';
        $sql = "SELECT p.*, c.name as category_name, 
                       pd.discount_percent,
                       (p.price * (1 - pd.discount_percent / 100)) as discounted_price
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
                WHERE (p.name LIKE ? OR p.brand LIKE ?) AND p.status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    //


    /**
     * Lấy tất cả các thương hiệu (brand) duy nhất để hiển thị trong bộ lọc.
     * @return array
     */
    public function getDistinctBrands() {
        $conn = db_connect();
        $sql = "SELECT DISTINCT brand FROM products WHERE brand IS NOT NULL AND brand != '' ORDER BY brand ASC";
        $result = $conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Lọc sản phẩm theo nhiều tiêu chí (danh mục, giá, thương hiệu).
     * Hàm này sẽ tự động xây dựng câu lệnh SQL dựa trên các bộ lọc được cung cấp.
     * @param array $filters - Mảng chứa các điều kiện lọc.
     * @return array - Mảng các sản phẩm đã được lọc.
     */
    public function filterProducts($filters) {
        $conn = db_connect();
        
        // Bắt đầu câu lệnh SQL cơ bản
        $sql = "SELECT p.*, c.name AS category_name, 
                       pd.discount_percent, 
                       (p.price * (1 - pd.discount_percent / 100)) as discounted_price
                FROM products p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
                WHERE p.status = 'active'";
        
        $params = []; // Mảng chứa các giá trị để bind vào câu lệnh
        $types = '';  // Chuỗi chứa kiểu dữ liệu của các tham số (ví dụ: 'iis')

        // Thêm điều kiện lọc theo DANH MỤC (nếu người dùng chọn)
        if (!empty($filters['categories'])) {
            $placeholders = implode(',', array_fill(0, count($filters['categories']), '?'));
            $sql .= " AND p.category_id IN ($placeholders)";
            $types .= str_repeat('i', count($filters['categories']));
            $params = array_merge($params, $filters['categories']);
        }
        
        // Thêm điều kiện lọc theo GIÁ TỐI ĐA (nếu người dùng kéo thanh trượt)
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $types .= 'd'; // d là kiểu double/float
            $params[] = $filters['max_price'];
        }

        // Thêm điều kiện lọc theo THƯƠNG HIỆU (nếu người dùng chọn)
        if (!empty($filters['brands'])) {
            $placeholders = implode(',', array_fill(0, count($filters['brands']), '?'));
            $sql .= " AND p.brand IN ($placeholders)";
            $types .= str_repeat('s', count($filters['brands'])); // s là kiểu string
            $params = array_merge($params, $filters['brands']);
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $conn->prepare($sql);

        // Chỉ bind param nếu có tham số
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>