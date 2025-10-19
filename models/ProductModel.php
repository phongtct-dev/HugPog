<?php

namespace App\Models;
// File: project/models/ProductModel.php

require_once __DIR__ . '/../includes/db_connect.php';

class ProductModel
{
    /**
     * Lấy các sản phẩm đang 'active' với giới hạn và vị trí bắt đầu (phân trang).
     * @param int $limit Số sản phẩm mỗi trang.
     * @param int $offset Vị trí bắt đầu lấy.
     * @return array
     */
    public function getAllActiveProducts($limit, $offset)
    {
        $conn = db_connect();
        $sql = "SELECT p.*, c.name AS category_name, pd.discount_percent, 
                        (p.price * (1 - pd.discount_percent / 100)) as discounted_price
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
                    WHERE p.status = 'active'
                    ORDER BY p.created_at DESC
                    LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
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
        $brands = $result->fetch_all(MYSQLI_ASSOC);
        $conn->close(); // 
        return $brands;
    }

    /**
     * Lọc sản phẩm theo nhiều tiêu chí VÀ hỗ trợ phân trang.
     * @param array $filters
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function filterProducts($filters, $limit, $offset)
    {
        $conn = db_connect();
        $sql = "SELECT p.*, c.name AS category_name, 
                   pd.discount_percent, 
                   (p.price * (1 - pd.discount_percent / 100)) as discounted_price
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_discounts pd ON p.id = pd.product_id AND CURDATE() BETWEEN pd.start_date AND pd.end_date
            WHERE p.status = 'active'";

        $params = [];
        $types = '';

        if (!empty($filters['categories'])) {
            $placeholders = implode(',', array_fill(0, count($filters['categories']), '?'));
            $sql .= " AND p.category_id IN ($placeholders)";
            $types .= str_repeat('i', count($filters['categories']));
            $params = array_merge($params, $filters['categories']);
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $types .= 'd';
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['brands'])) {
            $placeholders = implode(',', array_fill(0, count($filters['brands']), '?'));
            $sql .= " AND p.brand IN ($placeholders)";
            $types .= str_repeat('s', count($filters['brands']));
            $params = array_merge($params, $filters['brands']);
        }

        $sql .= " ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $types .= 'ii';
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $conn->prepare($sql);
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
        } catch (\Exception $e) {
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


    public function getRelatedProducts($categoryId, $currentProductId, $limit = 4)
    {
        $conn = db_connect(); // Sử dụng hàm kết nối MySQLi của dự án

        $sql = "SELECT id, name, price, image_url as image, stock
                FROM products
                WHERE category_id = ? AND id != ? AND status = 'active'
                ORDER BY RAND()
                LIMIT ?";

        $stmt = $conn->prepare($sql);

        // Ép kiểu $limit thành số nguyên để đảm bảo an toàn
        $limit = (int)$limit;

        // Gán các tham số vào câu lệnh SQL
        $stmt->bind_param("iii", $categoryId, $currentProductId, $limit);

        $stmt->execute();
        $result = $stmt->get_result();
        $products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $products;
    }

    /**
     * Đếm tổng số sản phẩm đang ở trạng thái 'active'.
     * @return int
     */
    public function countAllActiveProducts()
    {
        $conn = db_connect();
        $sql = "SELECT COUNT(id) as total FROM products WHERE status = 'active'";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }

    /**
     * Đếm tổng số sản phẩm sau khi áp dụng bộ lọc.
     * @param array $filters
     * @return int
     */
    public function countFilteredProducts($filters)
    {
        $conn = db_connect();
        $sql = "SELECT COUNT(p.id) as total FROM products p WHERE p.status = 'active'";

        $params = [];
        $types = '';

        if (!empty($filters['categories'])) {
            $placeholders = implode(',', array_fill(0, count($filters['categories']), '?'));
            $sql .= " AND p.category_id IN ($placeholders)";
            $types .= str_repeat('i', count($filters['categories']));
            $params = array_merge($params, $filters['categories']);
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $types .= 'd';
            $params[] = $filters['max_price'];
        }
        if (!empty($filters['brands'])) {
            $placeholders = implode(',', array_fill(0, count($filters['brands']), '?'));
            $sql .= " AND p.brand IN ($placeholders)";
            $types .= str_repeat('s', count($filters['brands']));
            $params = array_merge($params, $filters['brands']);
        }

        $stmt = $conn->prepare($sql);
        if ($types) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return (int)($row['total'] ?? 0);
    }
}
