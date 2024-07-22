<?php
// interfaces/IServiceOrder.php

interface IServiceOrder {
    public function create($customer_id, $mechanic_id, $service_type, $vehicle_model, $vehicle_license_plate, $description, $estimated_cost);
    public function createOrder($service_id, $user_id, $seller_id);
    public function getByUserId($user_id);
    public function getById($id);
    public function getAll();
    public function getByMechanic($mechanic_id);
    public function getServiceByCustomerId($customer_id);
    public function updateStatus($id, $status);
    public function update($id, $mechanic_id, $service_type, $vehicle_model, $vehicle_license_plate, $description, $estimated_cost, $status);
    public function delete($id);
    public function getTotalServices();
    public function updateEstimatedCost($id, $estimated_cost);
}
?>
