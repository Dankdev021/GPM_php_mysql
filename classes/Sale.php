<?php
// classes/Sale.php

require_once '../interfaces/ISale.php';

class Sale implements ISale {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($user_id, $material_id, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO sales (user_id, material_id, quantity) VALUES (?, ?, ?)");
        return $stmt->execute([$user_id, $material_id, $quantity]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT sales.*, users.username, materials.name 
                                  FROM sales 
                                  JOIN users ON sales.user_id = users.id 
                                  JOIN materials ON sales.material_id = materials.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalSales() {
        $stmt = $this->db->query("SELECT count(id) FROM orders");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM sales WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $user_id, $material_id, $quantity) {
        $stmt = $this->db->prepare("UPDATE sales SET user_id = ?, material_id = ?, quantity = ? WHERE id = ?");
        return $stmt->execute([$user_id, $material_id, $quantity, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM sales WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
