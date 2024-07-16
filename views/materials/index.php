<!-- views/materials/index.php -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor', 'cliente']);

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
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'cliente'): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $material): ?>
                <tr>
                    <td><?php echo htmlspecialchars($material['name']); ?></td>
                    <td><?php echo htmlspecialchars($material['description']); ?></td>
                    <td><?php echo htmlspecialchars($material['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($material['price']); ?></td>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <td>
                            <a href="edit.php?id=<?php echo $material['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <form action="../../controllers/MaterialController.php" method="POST" style="display:inline-block;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $material['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                            </form>
                        </td>
                    <?php elseif ($_SESSION['user']['role'] === 'cliente'): ?>
                        <td>
                            <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#buyModal" data-id="<?php echo $material['id']; ?>" data-name="<?php echo htmlspecialchars($material['name']); ?>" data-quantity="<?php echo $material['quantity']; ?>">Comprar</button>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
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
                        <input type="text" class="form-control" id="modalProductName" readonly>
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
    var buyModal = document.getElementById('buyModal');
    buyModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var name = button.getAttribute('data-name');
        var quantity = button.getAttribute('data-quantity');
        
        var modalProductId = buyModal.querySelector('#modalProductId');
        var modalProductName = buyModal.querySelector('#modalProductName');
        var modalQuantity = buyModal.querySelector('#modalQuantity');
        var quantityHelp = buyModal.querySelector('#quantityHelp');

        modalProductId.value = id;
        modalProductName.value = name;
        modalQuantity.setAttribute('max', quantity);
        quantityHelp.textContent = 'Quantidade disponível: ' + quantity;
    });
});
</script>

<?php
$customJS = ['materials/script.js'];
include '../footer.php';
?>
