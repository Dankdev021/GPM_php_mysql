<?php
session_start();
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['vendedor']);

$pageTitle = 'Minhas Vendas';
$customCSS = ['sales/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/Order.php';

$pdo = db_connect();
$orderModel = new Order($pdo);
$sales = $orderModel->getBySellerId($_SESSION['user']['id']);
?>

<div class="container mt-5">
    <h2 class="text-center">Minhas Vendas</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome do Material</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Data da Venda</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sales as $sale): ?>
                <tr>
                    <td><?php echo htmlspecialchars($sale['material_name']); ?></td>
                    <td><?php echo htmlspecialchars($sale['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($sale['material_price']); ?></td>
                    <td><?php echo htmlspecialchars($sale['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.php';
?>
