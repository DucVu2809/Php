<?php

/**
 * Model Sản phẩm — chứa các truy vấn phục vụ trang chủ, danh mục và chi tiết.
 */

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Product extends Model
{
    protected string $table = 'products';

    /**
     * Sản phẩm nổi bật (hiển thị ở trang chủ).
     *
     * @return array<int,array<string,mixed>>
     */
    public function featured(int $limit = 8): array
    {
        $sql = "SELECT p.*, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE p.is_active = 1 AND p.is_featured = 1
                ORDER BY p.created_at DESC
                LIMIT :limit";
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Sản phẩm mới nhất.
     *
     * @return array<int,array<string,mixed>>
     */
    public function latest(int $limit = 8): array
    {
        $sql = "SELECT p.*, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE p.is_active = 1
                ORDER BY p.created_at DESC
                LIMIT :limit";
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Lấy sản phẩm theo bộ lọc + phân trang.
     *
     * Hỗ trợ lọc theo danh mục, hãng, khoảng giá, từ khoá; sắp xếp linh hoạt.
     *
     * @param array{
     *     category_id?:int|null, brand_id?:int|null, keyword?:string|null,
     *     min_price?:int|null, max_price?:int|null, sort?:string|null,
     *     page?:int, per_page?:int
     * } $filters
     * @return array{items:array<int,array<string,mixed>>, total:int, page:int, pages:int}
     */
    public function paginate(array $filters = []): array
    {
        [$where, $params] = $this->buildWhere($filters);

        $page    = max(1, (int) ($filters['page'] ?? 1));
        $perPage = max(1, (int) ($filters['per_page'] ?? PRODUCTS_PER_PAGE));
        $offset  = ($page - 1) * $perPage;

        // Tổng số bản ghi khớp điều kiện (phục vụ phân trang)
        $countSql = "SELECT COUNT(*) AS total FROM `products` p WHERE {$where}";
        $total    = (int) Database::run($countSql, $params)->fetch()['total'];

        $orderBy = $this->buildOrderBy($filters['sort'] ?? null);

        $sql = "SELECT p.*, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE {$where}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = Database::connection()->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue('limit', $perPage, \PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return [
            'items' => $stmt->fetchAll(),
            'total' => $total,
            'page'  => $page,
            'pages' => (int) ceil($total / $perPage),
        ];
    }

    /**
     * Chi tiết sản phẩm theo slug, kèm tên danh mục và hãng.
     *
     * @return array<string,mixed>|null
     */
    public function findBySlug(string $slug): ?array
    {
        $sql = "SELECT p.*, c.name AS category_name, c.slug AS category_slug, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE p.slug = :slug AND p.is_active = 1
                LIMIT 1";
        $row = Database::run($sql, ['slug' => $slug])->fetch();
        return $row ?: null;
    }

    /**
     * Thư viện ảnh của một sản phẩm.
     *
     * @return array<int,array<string,mixed>>
     */
    public function images(int $productId): array
    {
        $sql = "SELECT * FROM `product_images`
                WHERE `product_id` = :id
                ORDER BY `sort_order` ASC";
        return Database::run($sql, ['id' => $productId])->fetchAll();
    }

    /**
     * Sản phẩm liên quan (cùng danh mục, loại trừ chính nó).
     *
     * @return array<int,array<string,mixed>>
     */
    public function related(int $categoryId, int $excludeId, int $limit = 4): array
    {
        $sql = "SELECT p.*, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE p.is_active = 1 AND p.category_id = :cat AND p.id <> :exclude
                ORDER BY p.is_featured DESC, p.created_at DESC
                LIMIT :limit";
        $stmt = Database::connection()->prepare($sql);
        $stmt->bindValue('cat', $categoryId, \PDO::PARAM_INT);
        $stmt->bindValue('exclude', $excludeId, \PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Tăng lượt xem của sản phẩm (gọi khi mở trang chi tiết).
     */
    public function incrementViews(int $id): void
    {
        Database::run("UPDATE `products` SET `views` = `views` + 1 WHERE `id` = :id", ['id' => $id]);
    }

    public function adminAll(string $keyword = ''): array
    {
        $where = '1';
        $params = []; // Khởi tạo mảng tuần tự không chứa Key dạng chữ

        if ($keyword !== '') {
            // Sử dụng 2 dấu hỏi chấm riêng biệt cho Tên và SKU
            $where = '(p.name LIKE ? OR p.sku LIKE ?)';
            
            // Đẩy đúng 2 tham số giá trị vào mảng theo thứ tự
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
        }

        $sql = "SELECT p.*, c.name AS category_name, b.name AS brand_name
                FROM `products` p
                LEFT JOIN `categories` c ON c.id = p.category_id
                LEFT JOIN `brands` b ON b.id = p.brand_id
                WHERE {$where}
                ORDER BY p.id DESC";

        // Gọi lại hàm chạy query chuẩn của hệ thống
        return Database::run($sql, $params)->fetchAll();
    }

    public function lowStock(int $threshold = 10): array
    {
        $stmt = Database::connection()->prepare(
            "SELECT * FROM `products` WHERE `stock` <= :t ORDER BY `stock` ASC LIMIT 8"
        );
        $stmt->bindValue('t', $threshold, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function increaseStock(int $id, int $quantity): void
    {
        Database::run("UPDATE `products` SET `stock` = `stock` + :q WHERE `id` = :id", ['q' => $quantity, 'id' => $id]);
    }

    /**
     * Dựng mệnh đề WHERE và mảng tham số từ bộ lọc.
     *
     * @param array<string,mixed> $filters
     * @return array{0:string,1:array<string,mixed>}
     */
    private function buildWhere(array $filters): array
    {
        $where  = ['p.is_active = 1'];
        $params = [];

        // LỌC SẢN PHẨM KHUYẾN MÃI: Kiểm tra xem có yêu cầu sắp xếp/lọc theo 'sale' không
        if (!empty($filters['sort']) && $filters['sort'] === 'sale') {
            // Điều kiện: sale_price phải tồn tại, lớn hơn 0 và nhỏ hơn giá gốc (price)
            $where[] = 'p.sale_price IS NOT NULL AND p.sale_price > 0 AND p.sale_price < p.price';
        }

        if (!empty($filters['category_id'])) {
            $where[]               = 'p.category_id = :category_id';
            $params['category_id'] = (int) $filters['category_id'];
        }
        if (!empty($filters['brand_id'])) {
            $where[]            = 'p.brand_id = :brand_id';
            $params['brand_id'] = (int) $filters['brand_id'];
        }
        if (!empty($filters['keyword'])) {
            // Dùng 2 placeholder riêng vì native prepare (MySQL/MariaDB)
            // không cho phép tái sử dụng cùng một tham số tên ở nhiều vị trí.
            $like                  = '%' . $filters['keyword'] . '%';
            $where[]               = '(p.name LIKE :kw_name OR p.short_desc LIKE :kw_desc)';
            $params['kw_name']     = $like;
            $params['kw_desc']     = $like;
        }
        if (!empty($filters['min_price'])) {
            $where[]             = 'COALESCE(p.sale_price, p.price) >= :min_price';
            $params['min_price'] = (int) $filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $where[]             = 'COALESCE(p.sale_price, p.price) <= :max_price';
            $params['max_price'] = (int) $filters['max_price'];
        }

        return [implode(' AND ', $where), $params];
    }

    /**
     * Chuyển khoá sắp xếp từ query string thành mệnh đề ORDER BY an toàn.
     */
    private function buildOrderBy(?string $sort): string
    {
        return match ($sort) {
            'price_asc'  => 'COALESCE(p.sale_price, p.price) ASC',
            'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
            'name_asc'   => 'p.name ASC',
            'popular'    => 'p.views DESC',
            'sale'       => '(p.price - p.sale_price) DESC',
            
            default      => 'p.is_featured DESC, p.created_at DESC',
        };
    }
}
