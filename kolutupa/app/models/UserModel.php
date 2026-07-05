<?php
// app/models/UserModel.php

class UserModel extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->query("SELECT * FROM users WHERE email = ?", [$email])->fetch() ?: null;
    }

    public function findByUsername(string $username): ?array {
        return $this->query("SELECT * FROM users WHERE LOWER(username) = LOWER(?)", [$username])->fetch() ?: null;
    }

    public function getFollowersCount(int $userId): int {
        return (int)$this->query("SELECT COUNT(*) FROM followers WHERE following_id = ?", [$userId])->fetchColumn();
    }

    public function getFollowingCount(int $userId): int {
        return (int)$this->query("SELECT COUNT(*) FROM followers WHERE follower_id = ?", [$userId])->fetchColumn();
    }

    public function isFollowing(int $followerId, int $followingId): bool {
        return (bool)$this->query(
            "SELECT id FROM followers WHERE follower_id = ? AND following_id = ?",
            [$followerId, $followingId]
        )->fetch();
    }

    public function follow(int $followerId, int $followingId): void {
        $this->query(
            "INSERT IGNORE INTO followers (follower_id, following_id) VALUES (?, ?)",
            [$followerId, $followingId]
        );
    }

    public function unfollow(int $followerId, int $followingId): void {
        $this->query(
            "DELETE FROM followers WHERE follower_id = ? AND following_id = ?",
            [$followerId, $followingId]
        );
    }

    public function updateAvatar(int $userId, string $path): void {
        $this->query("UPDATE users SET avatar = ? WHERE id = ?", [$path, $userId]);
    }

    public function getSellerAddress(int $userId): ?array {
        return $this->query(
            "SELECT * FROM seller_addresses WHERE user_id = ? AND is_primary = 1",
            [$userId]
        )->fetch() ?: null;
    }

    public function updateSellerAddress(int $userId, array $data): void {
        $existing = $this->getSellerAddress($userId);
        if ($existing) {
            $set = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
            $vals = array_values($data);
            $vals[] = $userId;
            $this->query("UPDATE seller_addresses SET {$set} WHERE user_id = ?", $vals);
        } else {
            $data['user_id'] = $userId;
            $cols = implode(', ', array_keys($data));
            $ph = implode(', ', array_fill(0, count($data), '?'));
            $this->query("INSERT INTO seller_addresses ({$cols}) VALUES ({$ph})", array_values($data));
        }
    }

    public function recalculateWalletBalance(int $userId): bool {
        $row = $this->query(
            "SELECT COALESCE(SUM(product_price), 0) AS total
             FROM orders
             WHERE seller_id = ? AND status = 'selesai'",
            [$userId]
        )->fetch();

        $total = (float)($row['total'] ?? 0);
        return $this->update($userId, ['wallet_balance' => number_format($total, 2, '.', '')]);
    }

    public function getReviewScore(int $userId): array {
        $row = $this->query(
            "SELECT AVG(rating) as avg_rating, COUNT(*) as total FROM reviews WHERE seller_id = ?",
            [$userId]
        )->fetch();
        return [
            'avg'   => round((float)($row['avg_rating'] ?? 0), 1),
            'total' => (int)($row['total'] ?? 0),
        ];
    }
}
