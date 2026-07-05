<?php
// app/models/Model.php

abstract class Model {
    protected PDO $db;
    protected string $table;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    protected function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function findById(int $id): ?array {
        return $this->query("SELECT * FROM {$this->table} WHERE id = ?", [$id])->fetch() ?: null;
    }

    public function hasActiveOrderForBuyerProduct(int $buyerId, int $productId): bool {
        if ($this->table !== 'orders') {
            return false;
        }
        return (bool)$this->query(
            "SELECT id FROM orders WHERE buyer_id = ? AND product_id = ? AND status != 'dibatalkan' LIMIT 1",
            [$buyerId, $productId]
        )->fetch();
    }

    public function all(string $orderBy = 'id DESC'): array {
        return $this->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function create(array $data): int {
        $cols = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $this->query("INSERT INTO {$this->table} ({$cols}) VALUES ({$placeholders})", array_values($data));
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(', ', array_map(fn($k) => "{$k} = ?", array_keys($data)));
        $values = array_values($data);
        $values[] = $id;
        return $this->query("UPDATE {$this->table} SET {$set} WHERE id = ?", $values)->rowCount() > 0;
    }

    public function delete(int $id): bool {
        return $this->query("DELETE FROM {$this->table} WHERE id = ?", [$id])->rowCount() > 0;
    }
}
