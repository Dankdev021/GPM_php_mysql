<?php
session_start();
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor']);

$pageTitle = 'Ordens de Serviço';
$customCSS = ['services/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/ServiceOrder.php';

$pdo = db_connect();
$serviceOrderModel = new ServiceOrder($pdo);

if ($_SESSION['user']['role'] === 'vendedor') {
    $serviceOrders = $serviceOrderModel->getByMechanic($_SESSION['user']['id']);
} else {
    $serviceOrders = $serviceOrderModel->getAll();
}
?>

<div class="container mt-5">
    <h2 class="text-center">Ordens de Serviço</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Mecânico</th>
                <th>Tipo de Serviço</th>
                <th>Modelo do Veículo</th>
                <th>Placa do Veículo</th>
                <th>Descrição</th>
                <th>Custo Estimado</th>
                <th>Status</th>
                <th>Data de Criação</th>
                <th>Última Atualização</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($serviceOrders as $serviceOrder): ?>
                <tr>
                    <td><?php echo htmlspecialchars($serviceOrder['id']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['mechanic_name']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['service_type']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['vehicle_model']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['vehicle_license_plate']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['description']); ?></td>
                    <td>R$ <?php echo number_format($serviceOrder['estimated_cost'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['status']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['updated_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>
