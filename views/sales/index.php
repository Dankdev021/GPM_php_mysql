<!-- views/sales/index.php -->

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Vendas';
$customCSS = ['sales/style.css'];
include '../header.php';

require_once '../../config/config.php';
require_once '../../classes/Sale.php';

$pdo = db_connect();
$saleModel = new Sale($pdo);
$sales = $saleModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Vendas</h2>
    <a href="create.php" class="btn btn-primary mb-3">Adicionar Venda</a>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Material</th>
                <th>Quantidade</th>
                <th>Data</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['username']); ?></td>
                    <td><?php echo htmlspecialchars($sale['name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($sale['sale_date']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $sale['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <form action="../../controllers/SaleController.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $sale['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>
