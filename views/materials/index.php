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
    <table class="table table-striped">
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
        <tbody>
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
        var buyButtons = document.querySelectorAll('.btn-primary[data-toggle="modal"]');
        
        buyButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var quantity = button.getAttribute('data-quantity');

                var modalProductId = document.getElementById('modalProductId');
                var modalProductName = document.getElementById('modalProductName');
                var modalQuantity = document.getElementById('modalQuantity');
                var quantityHelp = document.getElementById('quantityHelp');

                modalProductId.value = id;
                modalProductName.textContent = name;
                modalQuantity.setAttribute('max', quantity);
                quantityHelp.textContent = 'Quantidade disponível: ' + quantity;
            });
        });
    });
</script>

<?php include '../footer.php'; ?>
