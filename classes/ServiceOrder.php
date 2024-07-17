<?php
// classes/ServiceOrder.php

class ServiceOrder {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($service_id, $user_id, $seller_id) {
        $stmt = $this->db->prepare("INSERT INTO service_orders (service_id, user_id, seller_id) VALUES (?, ?, ?)");
        return $stmt->execute([$service_id, $user_id, $seller_id]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT service_orders.*, services.name as service_name, services.price as service_price FROM service_orders INNER JOIN services ON service_orders.service_id = services.id WHERE service_orders.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
