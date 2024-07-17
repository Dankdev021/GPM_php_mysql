<?php
// classes/Order.php

require_once __DIR__ . '/../interfaces/IOrder.php';

class Order implements IOrder {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($user_id, $seller_id, $product_id, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO orders (user_id, seller_id, product_id, quantity) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$user_id, $seller_id, $product_id, $quantity]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT orders.*, materials.name as material_name, materials.price as material_price FROM orders INNER JOIN materials ON orders.product_id = materials.id WHERE orders.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBySellerId($seller_id) {
        $stmt = $this->db->prepare("SELECT orders.*, materials.name as material_name, materials.price as material_price FROM orders INNER JOIN materials ON orders.product_id = materials.id WHERE orders.seller_id = ?");
        $stmt->execute([$seller_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
