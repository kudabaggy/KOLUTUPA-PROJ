<?php
// app/models/OrderModel.php

class OrderModel extends Model {
    protected string $table = 'orders';

    public function getByUser(int $userId, string $role = 'buyer', string $status = ''): array {
        $col = $role === 'seller' ? 'seller_id' : 'buyer_id';
        $sql = "SELECT o.*, p.title AS product_title, p.size AS product_size,
                       u_s.name AS seller_name, u_s.avatar AS seller_avatar,
                       u_b.name AS buyer_name,
                       (SELECT image_path FROM product_images pi WHERE pi.product_id = o.product_id AND pi.is_primary = 1 LIMIT 1) AS product_image,
                       (SELECT COUNT(*) FROM reviews r WHERE r.order_id = o.id) AS review_count
                FROM orders o
                JOIN products p ON p.id = o.product_id
                JOIN users u_s ON u_s.id = o.seller_id
                JOIN users u_b ON u_b.id = o.buyer_id
                WHERE o.{$col} = ?";
        $params = [$userId];
        if ($status) { $sql .= " AND o.status = ?"; $params[] = $status; }
        $sql .= " ORDER BY o.created_at DESC";
        return $this->query($sql, $params)->fetchAll();
    }

    public function generateInvoice(): string {
        return 'INV-' . strtoupper(uniqid());
    }

    public function createOrder(array $data): int {
        $data['invoice_number'] = $this->generateInvoice();
        $data['payment_deadline'] = date('Y-m-d H:i:s', strtotime('+1 day'));
        return $this->create($data);
    }

    public function hasActiveOrderForBuyerProduct(int $buyerId, int $productId): bool {
        return (bool)$this->query(
            "SELECT id FROM orders WHERE buyer_id = ? AND product_id = ? AND status != 'dibatalkan' LIMIT 1",
            [$buyerId, $productId]
        )->fetch();
    }
}

// app/models/CartModel.php
class CartModel extends Model {
    protected string $table = 'cart';

    public function getCartByUser(int $userId): array {
        return $this->query(
            "SELECT c.*, p.title, p.price, p.size, p.condition_item,
                    u.name AS seller_name, u.username AS seller_username,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = c.product_id AND pi.is_primary = 1 LIMIT 1) AS product_image
             FROM cart c
             JOIN products p ON p.id = c.product_id
             JOIN users u ON u.id = p.seller_id
             WHERE c.user_id = ? AND p.status = 'active'",
            [$userId]
        )->fetchAll();
    }

    public function addToCart(int $userId, int $productId): void {
        $this->query("INSERT IGNORE INTO cart (user_id, product_id) VALUES (?, ?)", [$userId, $productId]);
    }

    public function removeFromCart(int $userId, int $productId): void {
        $this->query("DELETE FROM cart WHERE user_id = ? AND product_id = ?", [$userId, $productId]);
    }

    public function countCart(int $userId): int {
        return (int)$this->query("SELECT COUNT(*) FROM cart WHERE user_id = ?", [$userId])->fetchColumn();
    }
}

// app/models/MessageModel.php
class MessageModel extends Model {
    protected string $table = 'messages';

    public function getConversations(int $userId): array {
        return $this->query(
            "SELECT m.*, 
                    CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END AS other_user_id,
                    u.name AS other_name, u.avatar AS other_avatar
             FROM messages m
             JOIN users u ON u.id = CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END
             WHERE m.id IN (
                 SELECT MAX(id) FROM messages
                 WHERE sender_id = ? OR receiver_id = ?
                 GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
             )
             ORDER BY m.created_at DESC",
            [$userId, $userId, $userId, $userId]
        )->fetchAll();
    }

    public function getThread(int $userId, int $otherId): array {
        return $this->query(
            "SELECT m.*, u.name AS sender_name, u.avatar AS sender_avatar
             FROM messages m JOIN users u ON u.id = m.sender_id
             WHERE (m.sender_id = ? AND m.receiver_id = ?) OR (m.sender_id = ? AND m.receiver_id = ?)
             ORDER BY m.created_at ASC",
            [$userId, $otherId, $otherId, $userId]
        )->fetchAll();
    }

    public function send(int $senderId, int $receiverId, string $content): int {
        return $this->create(['sender_id' => $senderId, 'receiver_id' => $receiverId, 'content' => $content]);
    }

    public function markRead(int $userId, int $senderId): void {
        $this->query("UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ?", [$userId, $senderId]);
    }

    public function countUnread(int $userId): int {
        return (int)$this->query("SELECT COUNT(*) FROM messages WHERE receiver_id = ? AND is_read = 0", [$userId])->fetchColumn();
    }
}

// app/models/NotificationModel.php
class NotificationModel extends Model {
    protected string $table = 'notifications';

    public function createNotification(int $userId, ?int $fromUserId, ?int $productId, string $type, string $message): int {
        return $this->create([
            'user_id' => $userId,
            'from_user_id' => $fromUserId,
            'product_id' => $productId,
            'type' => $type,
            'message' => $message,
            'is_read' => 0,
        ]);
    }

    public function getForUser(int $userId): array {
        return $this->query(
            "SELECT n.*, u.name AS from_name, u.avatar AS from_avatar,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = n.product_id AND pi.is_primary = 1 LIMIT 1) AS product_image
             FROM notifications n
             LEFT JOIN users u ON u.id = n.from_user_id
             WHERE n.user_id = ? ORDER BY n.created_at DESC",
            [$userId]
        )->fetchAll();
    }

    public function countUnread(int $userId): int {
        return (int)$this->query("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = 0", [$userId])->fetchColumn();
    }

    public function markAllRead(int $userId): void {
        $this->query("UPDATE notifications SET is_read = 1 WHERE user_id = ?", [$userId]);
    }
}

// app/models/ReviewModel.php
class ReviewModel extends Model {
    protected string $table = 'reviews';

    public function getForSeller(int $sellerId, int $limit = 10): array {
        return $this->query(
            "SELECT r.*, u.name AS reviewer_name, u.avatar AS reviewer_avatar,
                    p.title AS product_title,
                    (SELECT image_path FROM product_images pi
                     JOIN orders o2 ON o2.product_id = pi.product_id
                     WHERE o2.id = r.order_id AND pi.is_primary = 1 LIMIT 1) AS product_image
             FROM reviews r
             JOIN users u ON u.id = r.reviewer_id
             JOIN orders o ON o.id = r.order_id
             JOIN products p ON p.id = o.product_id
             WHERE r.seller_id = ?
             ORDER BY r.created_at DESC LIMIT ?",
            [$sellerId, $limit]
        )->fetchAll();
    }

    public function getForProduct(int $productId, int $limit = 10): array {
        return $this->query(
            "SELECT r.*, u.name AS reviewer_name, u.avatar AS reviewer_avatar,
                    p.title AS product_title,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS product_image
             FROM reviews r
             JOIN users u ON u.id = r.reviewer_id
             JOIN orders o ON o.id = r.order_id
             JOIN products p ON p.id = o.product_id
             WHERE o.product_id = ?
             ORDER BY r.created_at DESC LIMIT ?",
            [$productId, $limit]
        )->fetchAll();
    }

    public function getByReviewer(int $reviewerId, int $limit = 10): array {
        return $this->query(
            "SELECT r.*, u_s.name AS seller_name, u_s.avatar AS seller_avatar,
                    p.title AS product_title,
                    (SELECT image_path FROM product_images pi WHERE pi.product_id = p.id AND pi.is_primary = 1 LIMIT 1) AS product_image
             FROM reviews r
             JOIN users u_s ON u_s.id = r.seller_id
             JOIN orders o ON o.id = r.order_id
             JOIN products p ON p.id = o.product_id
             WHERE r.reviewer_id = ?
             ORDER BY r.created_at DESC LIMIT ?",
            [$reviewerId, $limit]
        )->fetchAll();
    }

    public function getRatingBreakdown(int $sellerId): array {
        $rows = $this->query(
            "SELECT rating, COUNT(*) as cnt FROM reviews WHERE seller_id = ? GROUP BY rating",
            [$sellerId]
        )->fetchAll();
        $breakdown = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
        foreach ($rows as $r) $breakdown[(int)$r['rating']] = (int)$r['cnt'];
        return $breakdown;
    }

    public function getRatingBreakdownForProduct(int $productId): array {
        $rows = $this->query(
            "SELECT r.rating, COUNT(*) as cnt
             FROM reviews r
             JOIN orders o ON o.id = r.order_id
             WHERE o.product_id = ?
             GROUP BY r.rating",
            [$productId]
        )->fetchAll();
        $breakdown = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
        foreach ($rows as $r) $breakdown[(int)$r['rating']] = (int)$r['cnt'];
        return $breakdown;
    }

    public function getReviewerRatingBreakdown(int $reviewerId): array {
        $rows = $this->query(
            "SELECT rating, COUNT(*) as cnt FROM reviews WHERE reviewer_id = ? GROUP BY rating",
            [$reviewerId]
        )->fetchAll();
        $breakdown = [5=>0, 4=>0, 3=>0, 2=>0, 1=>0];
        foreach ($rows as $r) $breakdown[(int)$r['rating']] = (int)$r['cnt'];
        return $breakdown;
    }

    public function getReviewScoreForProduct(int $productId): array {
        $row = $this->query(
            "SELECT AVG(r.rating) as avg_rating, COUNT(*) as total
             FROM reviews r
             JOIN orders o ON o.id = r.order_id
             WHERE o.product_id = ?",
            [$productId]
        )->fetch();
        return [
            'avg'   => round((float)($row['avg_rating'] ?? 0), 1),
            'total' => (int)($row['total'] ?? 0),
        ];
    }

    public function getReviewerScore(int $reviewerId): array {
        $row = $this->query(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE reviewer_id = ?",
            [$reviewerId]
        )->fetch();
        return [
            'avg'   => round((float)($row['avg_rating'] ?? 0), 1),
            'total' => (int)($row['total'] ?? 0),
        ];
    }

    public function hasReviewForOrder(int $orderId): bool {
        return (bool)$this->query("SELECT id FROM reviews WHERE order_id = ?", [$orderId])->fetch();
    }
}

// app/models/NegotiationModel.php
class NegotiationModel extends Model {
    protected string $table = 'negotiations';

    public function create(array $data): int {
        return parent::create($data);
    }

    public function getForSeller(int $sellerId): array {
        return $this->query(
            "SELECT n.*, p.title, u.name AS buyer_name, u.avatar AS buyer_avatar
             FROM negotiations n
             JOIN products p ON p.id = n.product_id
             JOIN users u ON u.id = n.buyer_id
             WHERE n.seller_id = ? AND n.status = 'pending'
             ORDER BY n.created_at DESC",
            [$sellerId]
        )->fetchAll();
    }

    public function getLatestBetweenUsers(int $userA, int $userB): ?array {
        return $this->query(
            "SELECT n.*, p.title AS product_title
             FROM negotiations n
             JOIN products p ON p.id = n.product_id
             WHERE (n.buyer_id = ? AND n.seller_id = ?) OR (n.buyer_id = ? AND n.seller_id = ?)
             ORDER BY n.created_at DESC LIMIT 1",
            [$userA, $userB, $userB, $userA]
        )->fetch() ?: null;
    }
}
