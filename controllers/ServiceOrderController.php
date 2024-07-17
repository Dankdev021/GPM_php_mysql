<?php
// controllers/ServiceOrderController.php

require_once '../config/Config.php';
require_once '../classes/ServiceOrder.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

try {
    switch ($action) {
        case 'create_service_order':
            session_start();
            $customer_id = $_POST['customer_id'];
            $mechanic_id = $_POST['mechanic_id'];
            $service_type = $_POST['service_type'];
            $vehicle_model = $_POST['vehicle_model'];
            $vehicle_license_plate = $_POST['vehicle_license_plate'];
            $description = $_POST['description'];
            $estimated_cost = $_POST['estimated_cost'];
    
            $pdo = db_connect();
            $serviceOrderModel = new ServiceOrder($pdo);
            if ($serviceOrderModel->create($customer_id, $mechanic_id, $service_type, $vehicle_model, $vehicle_license_plate, $description, $estimated_cost)) {
                header('Location: ../views/services/index.php');
            } else {
                echo "Erro ao criar ordem de serviço.";
            }
            break;
    
        default:
            echo "Ação não reconhecida.";
            break;
    }
} catch (\Throwable $th) {
    echo "Erro interno: ". $th->getMessage();
    error_log($th->getMessage(), 3, '../logs/error.log');
    exit;
}

?>
