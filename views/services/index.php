<?php
session_start();
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor', 'cliente']);

$pageTitle = 'Ordens de Serviço';
$customCSS = ['services/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/ServiceOrder.php';

$pdo = db_connect();
$serviceOrderModel = new ServiceOrder($pdo);

if ($_SESSION['user']['role'] === 'vendedor') {
    $serviceOrders = $serviceOrderModel->getByMechanic($_SESSION['user']['id']);
} else if ($_SESSION['user']['role'] === 'cliente') {
    $serviceOrders = $serviceOrderModel->getServiceByCustomerId($_SESSION['user']['id']);
} else {
    $serviceOrders = $serviceOrderModel->getAll();
}
?>

<div class="container container-wide mt-5" style="max-width: 80%;">
    <h2 class="text-center">Ordens de Serviço</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <div class="text-left mb-3" style="max-width: 300px;">
            <a href="../../scripts/download_service_orders.php" class="btn btn-success btn-sm">Baixar das Ordens de Serviço</a>
        </div>
    <?php endif; ?>
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
                <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                    <th style="padding: 40px;">Ações</th>
                <?php endif; ?>
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
                    <td>
                        <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                            <form action="../../controllers/ServiceOrderController.php" method="POST">
                                <input type="hidden" name="action" value="update_cost">
                                <input type="hidden" name="id" value="<?php echo $serviceOrder['id']; ?>">
                                <input type="text" <?php echo $serviceOrder['estimated_cost']; ?> name="estimated_cost" class="form-control" id="estimated_cost" placeholder="<?php echo $serviceOrder['estimated_cost']; ?>">
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Salvar</button>
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($serviceOrder['estimated_cost']); ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                            <form action="../../controllers/ServiceOrderController.php" method="POST">
                                <input type="hidden" name="action" value="update_status">
                                <input type="hidden" name="id" value="<?php echo $serviceOrder['id']; ?>">
                                <select name="status" class="form-control">
                                    <option value="pendente" <?php echo $serviceOrder['status'] === 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="concluido" <?php echo $serviceOrder['status'] === 'concluido' ? 'selected' : ''; ?>>Concluído</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm mt-2">Salvar</button>
                            </form>
                        <?php else: ?>
                            <?php echo htmlspecialchars($serviceOrder['status']); ?>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($serviceOrder['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($serviceOrder['updated_at']); ?></td>
                    <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                        <td style="display: flex;">
                            <a href="edit.php?id=<?php echo htmlspecialchars($serviceOrder['id']); ?>" class="btn btn-warning btn-sm" style="display: inline-block; margin-right: 5px;">Editar</a>
                            <form action="../../controllers/ServiceOrderController.php" method="POST" style="display: inline-block; margin: 0; padding: 0;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $serviceOrder['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm" style="display: inline-block;">Deletar</button>
                            </form>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var valorInput = document.getElementById('estimated_cost');

    valorInput.addEventListener('input', function(e) {
        var value = e.target.value;

        value = value.replace(/\D/g, ''); // Remove todos os caracteres que não são números

        if (value.length > 2) {
            value = value.replace(/^0+/, ''); // Remove zeros à esquerda
        }

        if (value.length <= 2) {
            value = ('00' + value).slice(-3);
        }

        value = value.replace(/(\d+)(\d{2})/, '$1,$2'); // Adiciona a vírgula

        e.target.value = value;
    });
});
</script>

<?php include '../footer.php'; ?>
