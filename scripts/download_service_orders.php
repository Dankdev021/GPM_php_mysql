<?php
require_once '../config/Config.php';
require_once '../classes/ServiceOrder.php';

$pdo = db_connect();
$serviceOrderModel = new ServiceOrder($pdo);
$serviceOrders = $serviceOrderModel->getAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="service_orders.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Cliente', 'Mecânico', 'Tipo de Serviço', 'Modelo do Veículo', 'Placa do Veículo', 'Descrição', 'Custo Estimado', 'Status', 'Data de Criação', 'Última Atualização']);

foreach ($serviceOrders as $serviceOrder) {
    fputcsv($output, [
        $serviceOrder['id'],
        $serviceOrder['customer_name'],
        $serviceOrder['mechanic_name'],
        $serviceOrder['service_type'],
        $serviceOrder['vehicle_model'],
        $serviceOrder['vehicle_license_plate'],
        $serviceOrder['description'],
        $serviceOrder['estimated_cost'],
        $serviceOrder['status'],
        $serviceOrder['created_at'],
        $serviceOrder['updated_at']
    ]);
}

fclose($output);
exit;
?>
