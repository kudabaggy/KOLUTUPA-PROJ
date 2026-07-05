<?php
// app/models/ProductModel.php

class ProductModel extends Model {
    protected string $table = 'products';

    public function getWithSeller(int $id): ?array {
        return $this->query(
            "SELECT p.*, u.username, u.name AS seller_name, u.avatar AS seller_avatar,
                    u.rating AS seller_rating, sa.city AS seller_city
             FROM products p
             JOIN users u ON p.seller_id = u.id
             LEFT JOIN seller_addresses sa ON sa.user_id = u.id AND sa.is_primary = 1
             WHERE p.id = ? AND p.status != 'draft'" ,
            [$id]
        )->fetch() ?: null;
    }

    public function getPrimaryImage(int $productId): string {
        $img = $this->query(
            "SELECT image_path FROM product_images WHERE product_id = ? AND is_primary = 1 LIMIT 1",
            [$productId]
        )->fetchColumn();
        return $img ?: 'assets/images/placeholder.jpg';
    }

    public function getAllImages(int $productId): array {
        return $this->query(
            "SELECT * FROM product_images WHERE product_id = ? ORDER BY sort_order",
            [$productId]
        )->fetchAll();
    }

    public function getMeasurements(int $productId): array {
        return $this->query(
            "SELECT * FROM product_measurements WHERE product_id = ?",
            [$productId]
        )->fetchAll();
    }

    public function getByCategory(string $category, array $filters = [], int $limit = 20, int $offset = 0): array {
        $where = ["p.status != 'draft'"];
        $params = [];

        if ($category !== 'all') {
            if ($category === 'Negotiable' || $category === 'Sale') {
                $where[] = "p.is_negotiable = 1";
            } else {
                $where[] = "p.category = ?";
                $params[] = $category;
            }
        }
        if (!empty($filters['size'])) { $where[] = "p.size = ?"; $params[] = $filters['size']; }
        if (!empty($filters['condition'])) { $where[] = "p.condition_item = ?"; $params[] = $filters['condition']; }
        if (!empty($filters['min_price'])) { $where[] = "p.price >= ?"; $params[] = $filters['min_price']; }
        if (!empty($filters['max_price'])) { $where[] = "p.price <= ?"; $params[] = $filters['max_price']; }
        if (!empty($filters['brand'])) { $where[] = "p.brand LIKE ?"; $params[] = '%' . $filters['brand'] . '%'; }

        $sql = "SELECT p.*, u.username, u.name AS seller_name,
                       (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS primary_image
                FROM products p
                JOIN users u ON p.seller_id = u.id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->query($sql, $params)->fetchAll();
    }

    public function getBySellerWithImages(int $sellerId): array {
        return $this->query(
            "SELECT p.*,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS primary_image
             FROM products p
             WHERE p.seller_id = ? AND p.status != 'draft'
             ORDER BY p.created_at DESC",
            [$sellerId]
        )->fetchAll();
    }

    public function getHotItems(int $limit = 10): array {
        return $this->query(
            "SELECT p.*,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS primary_image
             FROM products p
             WHERE p.status != 'draft'
             ORDER BY p.created_at DESC LIMIT ?",
            [$limit]
        )->fetchAll();
    }

    public function search(string $q, int $limit = 20): array {
        return $this->query(
            "SELECT p.*,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS primary_image
             FROM products p
             WHERE p.status != 'draft' AND (p.title LIKE ? OR p.description LIKE ? OR p.brand LIKE ?)
             ORDER BY p.created_at DESC LIMIT ?",
            ["%$q%", "%$q%", "%$q%", $limit]
        )->fetchAll();
    }

    public function isLiked(int $userId, int $productId): bool {
        return (bool)$this->query(
            "SELECT id FROM likes WHERE user_id = ? AND product_id = ?",
            [$userId, $productId]
        )->fetch();
    }

    public function toggleLike(int $userId, int $productId): bool {
        if ($this->isLiked($userId, $productId)) {
            $this->query("DELETE FROM likes WHERE user_id = ? AND product_id = ?", [$userId, $productId]);
            return false;
        }
        $this->query("INSERT INTO likes (user_id, product_id) VALUES (?, ?)", [$userId, $productId]);
        return true;
    }

    public function getLikedByUser(int $userId): array {
        return $this->query(
            "SELECT p.*,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS primary_image
             FROM products p
             JOIN likes l ON l.product_id = p.id
             WHERE l.user_id = ? AND p.status != 'draft'
             ORDER BY l.created_at DESC",
            [$userId]
        )->fetchAll();
    }

    public function getRecommendedSellers(int $limit = 4): array {
        return $this->query(
            "SELECT u.id, u.username, u.name, u.avatar,
                    COUNT(DISTINCT o.id) AS total_transactions,
                    COALESCE(AVG(r.rating), 0) AS avg_rating,
                    (SELECT image_path FROM product_images pi
                     JOIN products pp ON pp.id = pi.product_id
                     WHERE pp.seller_id = u.id AND pi.is_primary = 1 LIMIT 1) AS sample_image
             FROM users u
             LEFT JOIN orders o ON o.seller_id = u.id AND o.status != 'dibatalkan'
             LEFT JOIN reviews r ON r.seller_id = u.id
             GROUP BY u.id
             ORDER BY total_transactions DESC, avg_rating DESC
             LIMIT ?",
            [$limit]
        )->fetchAll();
    }

    public function saveImages(int $productId, array $paths, bool $first = true): void {
        foreach ($paths as $i => $path) {
            $this->query(
                "INSERT INTO product_images (product_id, image_path, is_primary, sort_order) VALUES (?, ?, ?, ?)",
                [$productId, $path, ($i === 0 && $first) ? 1 : 0, $i]
            );
        }
    }

    public function saveMeasurements(int $productId, array $measurements): void {
        $this->query("DELETE FROM product_measurements WHERE product_id = ?", [$productId]);
        foreach ($measurements as $label => $value) {
            if ($value !== '') {
                $this->query(
                    "INSERT INTO product_measurements (product_id, label, value) VALUES (?, ?, ?)",
                    [$productId, $label, $value]
                );
            }
        }
    }
}
