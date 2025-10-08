<?php
// File: project/models/ProductModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class ProductModel
{
    /**
     * Lấy tất cả sản phẩm đang ở trạng thái 'active'.
     * @return array Mảng chứa danh sách các sản phẩm.
     */
     public function getAllActiveProducts()
    {
        $conn = db_connect();
        $sql = "SELECT p.*, p.image_url, c.name AS category_name, p.brand,
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

    /**
     * Tìm một sản phẩm duy nhất dựa vào ID.
     * @param int $id ID của sản phẩm cần tìm.
     * @return array|null Mảng chứa thông tin sản phẩm, hoặc null nếu không tìm thấy.
     */
    public function findProductById($id)
    {
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

    /**
     * Lấy TẤT CẢ sản phẩm cho trang admin (bao gồm cả active và inactive).
     * @return array
     */
    public function getAllProductsForAdmin()
    {
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
    public function createProduct($data)
    {
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
    public function updateProduct($id, $data)
    {
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

    /**
     * Tìm kiếm sản phẩm theo từ khóa.
     * @param string $keyword
     * @return array
     */
    public function searchProducts($keyword)
    {
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

    /**
     * Lấy tất cả các thương hiệu (brand) duy nhất để hiển thị trong bộ lọc.
     * @return array
     */
    public function getDistinctBrands()
    {
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
    public function filterProducts($filters)
    {
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

    /**
     * Hàm lấy tổng số sản phẩm trong bảng products.
     * @return int Tổng số sản phẩm, hoặc 0 nếu có lỗi.
     */
    /**
     * Hàm lấy tổng số sản phẩm trong bảng products.
     * SỬ DỤNG MySQLi thông qua hàm db_connect()
     * @return int Tổng số sản phẩm, hoặc 0 nếu có lỗi.
     */
    public function getTotalProducts()
    {
        // 1. Lấy đối tượng kết nối MySQLi (không dùng global $pdo;)
        $conn = db_connect();

        $query = "SELECT COUNT(*) as total FROM products";

        try {
            // 2. Thực thi truy vấn bằng MySQLi
            $result = $conn->query($query);

            if ($result === false) {
                // Xử lý lỗi truy vấn SQL
                error_log("Lỗi truy vấn tổng sản phẩm: " . $conn->error);
                return 0;
            }

            // 3. Lấy kết quả bằng MySQLi
            $row = $result->fetch_assoc();
            $total = $row['total'] ?? 0;

            $result->free();
            return (int)$total;
        } catch (Exception $e) {
            error_log("Lỗi lấy tổng số sản phẩm: " . $e->getMessage());
            return 0;
        } finally {
            // 4. Đảm bảo đóng kết nối sau khi hoàn thành
            if (isset($conn)) {
                $conn->close();
            }
        }
    }

    public function getRecentProducts($limit = 5)
    {
        $conn = db_connect(); // Gọi hàm kết nối CSDL

        // Lấy thêm thông tin giảm giá để hiển thị giá cuối cùng (tương tự getAllActiveProducts)
        $sql = "SELECT p.*, c.name AS category_name, 
                   pd.discount_percent, 
                   (p.price * (1 - pd.discount_percent / 100)) as discounted_price
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
            WHERE p.status = 'active'
            ORDER BY p.created_at DESC
            LIMIT ?";

        $stmt = $conn->prepare($sql);

        // Bind tham số cho LIMIT
        $stmt->bind_param('i', $limit);
        $stmt->execute();

        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $products;
    }

    /**
     * Xóa một sản phẩm theo ID.
     * @param int $productId
     * @return bool
     */
    public function deleteProduct($productId)
    {
        $conn = db_connect();
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $productId);
        $success = $stmt->execute();
        $stmt->close();
        $conn->close();
        return $success;
    }

    public function getRelatedProducts($category, $currentProductId, $limit = 4)
    {
        global $pdo;

        // Tránh injection cho LIMIT -> ép kiểu integer
        $limit = (int)$limit;
        $sql = "SELECT id, name, category, price, discount, image, stock
                FROM products
                WHERE category = ? AND id != ?
                ORDER BY RAND()
                LIMIT {$limit}";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$category, $currentProductId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
