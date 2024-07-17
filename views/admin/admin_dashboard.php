<?php
session_start();
require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin']);

$pageTitle = 'Admin Dashboard';
$customCSS = ['admin_dashboard/style.css'];

require_once '../config/Config.php';
require_once '../classes/Order.php';

$pdo = db_connect();
$orderModel = new Order($pdo);
$orders = $orderModel->getAllOrders();
$totalSalesAmount = $orderModel->getTotalSalesAmount();
?>

<div class="container mt-5">
    <h2 class="text-center">Admin Dashboard</h2>
    <div class="text-center mt-4">
        <h3>Total de Vendas: R$ <?php echo number_format($totalSalesAmount, 2, ',', '.'); ?></h3>
    </div>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID da Venda</th>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Vendedor</th>
                <th>Cliente</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['product_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                    <td>R$ <?php echo number_format($order['price'], 2, ',', '.'); ?></td>
                    <td><?php echo htmlspecialchars($order['seller_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
