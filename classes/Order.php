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

    public function getAllOrders() {
        $stmt = $this->db->query("SELECT orders.id, products.name as product_name, orders.quantity, orders.price, sellers.username as seller_name, customers.username as customer_name, orders.created_at FROM orders INNER JOIN products ON orders.product_id = products.id INNER JOIN users as sellers ON orders.seller_id = sellers.id INNER JOIN users as customers ON orders.customer_id = customers.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalSalesAmount() {
        $stmt = $this->db->query("SELECT SUM(price * quantity) as total FROM orders");
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
?>
