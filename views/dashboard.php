<?php
session_start();
require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor']);

$pageTitle = 'Dashboard';
$customCSS = ['admin_css/style.css'];
include 'header.php';

$role = $_SESSION['user']['role'];

require_once '../config/Config.php';
require_once '../classes/Material.php';
require_once '../classes/ServiceOrder.php';
require_once '../classes/User.php';
require_once '../classes/Order.php';
require_once '../classes/Sale.php';

$pdo = db_connect();
$materialModel = new Material($pdo);
$serviceModel = new ServiceOrder($pdo);
$userModel = new User($pdo);
$orderModel = new Order($pdo);
$saleModel = new Sale($pdo);

$totalSalesValue = $orderModel->getTotalSalesValue();
$totalServices = count($serviceModel->getTotalServices());
$totalUsers = count($userModel->getAll());
$totalMaterials = count($materialModel->getAll());
$totalSales = count($saleModel->getTotalSales());
?>

<div class="container mt-5">
    <?php if ($role === 'admin'): ?>
        <div class="container mt-5">
        <h2 class="text-center">Dashboard do Administrador</h2>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">
                        <strong>Gerenciar Materiais</strong>
                    </div>
                    <div class="card-body">
                        <p>Total de Materiais: <?php echo $totalMaterials; ?></p>
                        <a href="materials/index.php" class="btn btn-primary mb-2">Ver Materiais</a>
                        <a href="materials/create.php" class="btn btn-success">Adicionar Material</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">
                        <strong> Serviços </strong>
                    </div>
                    <div class="card-body">
                        <p>Total de Serviços: <?php echo $totalServices; ?></p>
                        <a href="services/index.php" class="btn btn-primary mb-2">Ver Serviços</a>
                        <a href="services/create.php" class="btn btn-success">Adicionar Serviço</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">
                        <strong> Gerenciar Usuários </strong>
                    </div>
                    <div class="card-body">
                        <p>Total de Usuários: <?php echo $totalUsers; ?></p>
                        <a href="users/index.php" class="btn btn-primary mb-2">Ver Usuários</a>
                        <a href="users/create.php" class="btn btn-success">Adicionar Usuário</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-header">
                        <strong>Gerenciar Vendas</strong> 
                    </div>
                    <div class="card mt-4">
                    <div class="card-body">
                        <p>Total de Vendas: <?php echo $totalSales; ?></p>
                        <p>Valor Total das Vendas: R$ <?php echo number_format($totalSalesValue, 2, ',', '.'); ?></p>
                        <a href="sales/index.php" class="btn btn-primary">Ver Vendas</a>
                    </div>
                </div>
                </div>
            </div>
        </div>
</div>
    <?php endif; ?>

    <?php if ($role === 'vendedor'): ?>
        <div class="col-md-6 mt-4">
            <div class="card text-center">
                <div class="card-header">
                    <strong>Total de Vendas</strong>
                </div>
                <div class="card-body">
                    <p><?php echo $totalSales; ?></p>
                    <p>R$ <?php echo number_format($totalSalesValue, 2, ',', '.'); ?></p>
                    <a href="sales/index.php" class="btn btn-primary">Ver Vendas</a>
                    <a href="../../controllers/download_sales.php" class="btn btn-success mt-2">Download das Vendas</a>
                </div>
            </div>
                </div>
            </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
