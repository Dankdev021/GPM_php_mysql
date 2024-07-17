<?php
// classes/ServiceOrder.php

class ServiceOrder {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($customer_id, $mechanic_id, $service_type, $vehicle_model, $vehicle_license_plate, $description, $estimated_cost) {
        $stmt = $this->db->prepare("INSERT INTO service_orders (customer_id, mechanic_id, service_type, vehicle_model, vehicle_license_plate, description, estimated_cost) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$customer_id, $mechanic_id, $service_type, $vehicle_model, $vehicle_license_plate, $description, $estimated_cost]);
    }

        public function createOrder($service_id, $user_id, $seller_id) {
        $stmt = $this->db->prepare("INSERT INTO service_orders (service_id, user_id, seller_id) VALUES (?, ?, ?)");
        return $stmt->execute([$service_id, $user_id, $seller_id]);
    }

    public function getByUserId($user_id) {
        $stmt = $this->db->prepare("SELECT service_orders.*, services.name as service_name, services.price as service_price FROM service_orders INNER JOIN services ON service_orders.service_id = services.id WHERE service_orders.user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT service_orders.*, customers.username as customer_name, mechanics.username as mechanic_name FROM service_orders INNER JOIN users as customers ON service_orders.customer_id = customers.id INNER JOIN users as mechanics ON service_orders.mechanic_id = mechanics.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
