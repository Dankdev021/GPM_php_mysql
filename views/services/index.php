<?php
session_start();
require_once '../../config/auth.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
        <tbody id="serviceOrdersBody">
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

    <!-- Paginação -->
    <nav aria-label="Navegação de página">
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Paginação será gerada dinamicamente -->
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const serviceOrders = <?php echo json_encode($serviceOrders); ?>;
    const itemsPerPage = 6;
    let currentPage = 1;

    function renderTable(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedItems = serviceOrders.slice(start, end);

        const tbody = document.getElementById('serviceOrdersBody');
        tbody.innerHTML = '';

        paginatedItems.forEach(serviceOrder => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${serviceOrder.id}</td>
                <td>${serviceOrder.customer_name}</td>
                <td>${serviceOrder.mechanic_name}</td>
                <td>${serviceOrder.service_type}</td>
                <td>${serviceOrder.vehicle_model}</td>
                <td>${serviceOrder.vehicle_license_plate}</td>
                <td>${serviceOrder.description}</td>
                <td>
                    <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                        <form action="../../controllers/ServiceOrderController.php" method="POST">
                            <input type="hidden" name="action" value="update_cost">
                            <input type="hidden" name="id" value="${serviceOrder.id}">
                            <input type="text" value="${serviceOrder.estimated_cost}" name="estimated_cost" class="form-control" id="estimated_cost" placeholder="${serviceOrder.estimated_cost}">
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Salvar</button>
                        </form>
                    <?php else: ?>
                        ${serviceOrder.estimated_cost}
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                        <form action="../../controllers/ServiceOrderController.php" method="POST">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="id" value="${serviceOrder.id}">
                            <select name="status" class="form-control">
                                <option value="pendente" ${serviceOrder.status === 'pendente' ? 'selected' : ''}>Pendente</option>
                                <option value="concluido" ${serviceOrder.status === 'concluido' ? 'selected' : ''}>Concluído</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm mt-2">Salvar</button>
                        </form>
                    <?php else: ?>
                        ${serviceOrder.status}
                    <?php endif; ?>
                </td>
                <td>${serviceOrder.created_at}</td>
                <td>${serviceOrder.updated_at}</td>
                <?php if ($_SESSION['user']['role'] !== 'cliente'): ?>
                    <td style="display: flex;">
                        <a href="edit.php?id=${serviceOrder.id}" class="btn btn-warning btn-sm" style="display: inline-block; margin-right: 5px;">Editar</a>
                        <form action="../../controllers/ServiceOrderController.php" method="POST" style="display: inline-block; margin: 0; padding: 0;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="${serviceOrder.id}">
                            <button type="submit" class="btn btn-danger btn-sm" style="display: inline-block;">Deletar</button>
                        </form>
                    </td>
                <?php endif; ?>
            `;
            tbody.appendChild(row);
        });
    }

    function renderPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(serviceOrders.length / itemsPerPage);

        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" href="#" tabindex="-1">Anterior</a>`;
        prevPageItem.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderTable(currentPage);
                renderPagination();
            }
        });
        pagination.appendChild(prevPageItem);

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === currentPage ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', function() {
                currentPage = i;
                renderTable(currentPage);
                renderPagination();
            });
            pagination.appendChild(pageItem);
        }

        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" href="#">Próxima</a>`;
        nextPageItem.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable(currentPage);
                renderPagination();
            }
        });
        pagination.appendChild(nextPageItem);
    }

    renderTable(currentPage);
    renderPagination();
});
</script>

<?php include '../footer.php'; ?>
