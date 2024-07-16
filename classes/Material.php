<?php
// classes/Material.php

require_once __DIR__ . '/../interfaces/IMaterial.php';

class Material implements IMaterial {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($name, $description, $quantity, $price) {
        $stmt = $this->db->prepare("INSERT INTO materials (name, description, quantity, price) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$name, $description, $quantity, $price]);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM materials");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM materials WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $name, $description, $quantity, $price) {
        $stmt = $this->db->prepare("UPDATE materials SET name = ?, description = ?, quantity = ?, price = ? WHERE id = ?");
        return $stmt->execute([$name, $description, $quantity, $price, $id]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM materials WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateQuantity($id, $quantity) {
        $stmt = $this->db->prepare("UPDATE materials SET quantity = ? WHERE id = ?");
        return $stmt->execute([$quantity, $id]);
    }
}
?>
