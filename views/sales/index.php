<?php
session_start();
require_once '../../config/auth.php';

$pageTitle = 'Minhas Vendas';
include '../header.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['vendedor', 'admin']);

require_once '../../config/Config.php';
require_once '../../classes/Order.php';

$pdo = db_connect();
$orderModel = new Order($pdo);

if ($_SESSION['user']['role'] === 'vendedor') {
    $sales = $orderModel->getBySellerId($_SESSION['user']['id']);
} else {
    $sales = $orderModel->getAll();
}
?>

<div class="container mt-5">
    <h2 class="text-center">Minhas Vendas</h2>
    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <div class="text-right mb-3" style="width: 160px;">
            <a href="../../scripts/download_sales.php" class="btn btn-success">Baixar relatório</a>
        </div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome do Material</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Data da Venda</th>
            </tr>
        </thead>
        <tbody id="salesBody">
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['material_name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                    <td>R$ <?php echo number_format($sale['material_price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($sale['created_at']); ?></td>
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
        const sales = <?php echo json_encode($sales); ?>;
        const itemsPerPage = 6;
        let currentPage = 1;

        function renderTable(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = sales.slice(start, end);

            const tbody = document.getElementById('salesBody');
            tbody.innerHTML = '';

            paginatedItems.forEach(sale => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${sale.material_name}</td>
                    <td>${sale.quantity}</td>
                    <td>R$ ${parseFloat(sale.material_price).toFixed(2).replace('.', ',')}</td>
                    <td>${sale.created_at}</td>
                `;
                tbody.appendChild(row);
            });
        }

        function renderPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const totalPages = Math.ceil(sales.length / itemsPerPage);

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

<?php
include '../footer.php';
?>
