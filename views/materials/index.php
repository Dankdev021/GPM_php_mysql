<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

// Verifica se o usuário está logado
$isUserLoggedIn = isset($_SESSION['user']);
$userRole = $isUserLoggedIn ? $_SESSION['user']['role'] : null;

$pageTitle = 'Materiais';
$customCSS = ['materials/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/Material.php';

$pdo = db_connect();
$materialModel = new Material($pdo);
$materials = $materialModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Materiais Disponíveis</h2>
    <?php if ($isUserLoggedIn && $userRole === 'admin'): ?>
        <div class="text-left mb-3" style="max-width: 200px;">
            <a href="../../scripts/download_materials.php" class="btn btn-success btn-sm">Download dos Materiais</a>
        </div>
    <?php endif; ?>
    <table class="table table-striped" id="materialsTable">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <?php if ($isUserLoggedIn && ($userRole === 'admin' || $userRole === 'cliente')): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody id="materialsBody">
            <?php foreach ($materials as $material): ?>
                <tr>
                    <td><?php echo htmlspecialchars($material['name']); ?></td>
                    <td><?php echo htmlspecialchars($material['description']); ?></td>
                    <td><?php echo htmlspecialchars($material['quantity']); ?></td>
                    <td>R$ <?php echo number_format($material['price'], 2, ',', '.'); ?></td>
                    <?php if ($isUserLoggedIn && $userRole === 'admin'): ?>
                        <td>
                            <a href="edit.php?id=<?php echo $material['id']; ?>" class="btn btn-warning btn-sm" style="display: inline-block; margin-right: 5px;">Editar</a>
                            <form action="../../controllers/MaterialController.php" method="POST" style="display: inline-block; margin: 0;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $material['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                            </form>
                        </td>
                    <?php elseif ($isUserLoggedIn && $userRole === 'cliente'): ?>
                        <td>
                            <?php if ($material['quantity'] > 0): ?>
                                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#buyModal" data-id="<?php echo $material['id']; ?>" data-name="<?php echo htmlspecialchars($material['name']); ?>" data-quantity="<?php echo $material['quantity']; ?>">Comprar</button>
                            <?php else: ?>
                                <span class="text-danger">Sem estoque</span>
                            <?php endif; ?>
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

<!-- Modal de Compra -->
<div class="modal fade" id="buyModal" tabindex="-1" role="dialog" aria-labelledby="buyModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="buyModalLabel">Comprar Material</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../../controllers/OrderController.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="buy">
                    <input type="hidden" name="product_id" id="modalProductId">
                    <div class="form-group">
                        <label for="modalProductName">Nome</label>
                        <p id="modalProductName"></p>
                    </div>
                    <div class="form-group">
                        <label for="seller_id">Vendedor</label>
                        <select name="seller_id" class="form-control" required>
                            <option value="" selected disabled>Selecione um Vendedor</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'vendedor'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantidade</label>
                        <input type="number" class="form-control" name="quantity" id="modalQuantity" required min="1">
                    </div>
                    <small id="quantityHelp" class="form-text text-muted"></small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Comprar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const materials = <?php echo json_encode($materials); ?>;
        const itemsPerPage = 6;
        let currentPage = 1;

        function renderTable(page) {
            const start = (page - 1) * itemsPerPage;
            const end = start + itemsPerPage;
            const paginatedItems = materials.slice(start, end);

            const tbody = document.getElementById('materialsBody');
            tbody.innerHTML = '';

            paginatedItems.forEach(material => {
                const row = document.createElement('tr');

                row.innerHTML = `
                    <td>${material.name}</td>
                    <td>${material.description}</td>
                    <td>${material.quantity}</td>
                    <td>R$ ${parseFloat(material.price).toFixed(2).replace('.', ',')}</td>
                    <?php if ($isUserLoggedIn && $userRole === 'admin'): ?>
                    <td>
                        <a href="edit.php?id=${material.id}" class="btn btn-warning btn-sm" style="display: inline-block; margin-right: 5px;">Editar</a>
                        <form action="../../controllers/MaterialController.php" method="POST" style="display: inline-block; margin: 0;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="${material.id}">
                            <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                        </form>
                    </td>
                    <?php elseif ($isUserLoggedIn && $userRole === 'cliente'): ?>
                    <td>
                        ${material.quantity > 0 ? `<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#buyModal" data-id="${material.id}" data-name="${material.name}" data-quantity="${material.quantity}">Comprar</button>` : `<span class="text-danger">Sem estoque</span>`}
                    </td>
                    <?php endif; ?>
                `;
                tbody.appendChild(row);
            });
        }

        function renderPagination() {
            const pagination = document.getElementById('pagination');
            pagination.innerHTML = '';

            const totalPages = Math.ceil(materials.length / itemsPerPage);

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

        $('#buyModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var quantity = button.data('quantity');

            var modal = $(this);
            modal.find('#modalProductId').val(id);
            modal.find('#modalProductName').text(name);
            modal.find('#modalQuantity').attr('max', quantity);
            modal.find('#quantityHelp').text('Quantidade disponível: ' + quantity);
        });

        document.getElementById('modalQuantity').addEventListener('input', function() {
            var quantity = parseInt(this.value, 10);
            var maxQuantity = parseInt(this.getAttribute('max'), 10);

            if (quantity < 1 || quantity > maxQuantity) {
                this.value = Math.min(Math.max(quantity, 1), maxQuantity);
            }
        });
    });
</script>

<?php include '../footer.php'; ?>
