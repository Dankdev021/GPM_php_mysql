<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['cliente']);

$pageTitle = 'Minhas Compras';
$customCSS = ['purchases/style.css'];
include 'header.php';

require_once '../config/Config.php';
require_once '../classes/Order.php';

$pdo = db_connect();
$orderModel = new Order($pdo);
$purchases = $orderModel->getByUserId($_SESSION['user']['id']);
?>

<div class="container mt-5">
    <h2 class="text-center">Minhas Compras</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nome do Material</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Data da Compra</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($purchases as $purchase): ?>
                <tr>
                    <td><?php echo htmlspecialchars($purchase['material_name']); ?></td>
                    <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                    <td><?php echo htmlspecialchars($purchase['material_price']); ?></td>
                    <td><?php echo htmlspecialchars($purchase['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include 'footer.php';
?>
