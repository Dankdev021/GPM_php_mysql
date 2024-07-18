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
require_once '../classes/Service.php';
require_once '../classes/User.php';
require_once '../classes/Order.php';

$pdo = db_connect();
$materialModel = new Material($pdo);
$serviceModel = new Service($pdo);
$userModel = new User($pdo);
$orderModel = new Order($pdo);
?>

<div class="container mt-5">
    <!-- <h2 class="text-center">Dashboard</h2>
    <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p> -->

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
        <div class="col-md-6 mt-4">
            <div class="card text-center">
                <div class="card-header">
                    <strong>Gerenciar Vendas</strong> 
                </div>
                <div class="card-body">
                    <p>Total de Vendas: <?php echo $totalSales; ?></p>
                    <p>Valor Total das Vendas: R$ <?php echo number_format($totalSalesValue, 2, ',', '.'); ?></p>
                    <a href="sales/index.php" class="btn btn-primary">Ver Vendas</a>
                </div>
            </div>
        </div>
    </div>
</div>
    <?php endif; ?>

    <?php if ($role === 'vendedor'): ?>
        <div class="text-center mt-4">
            <a href="sales/index.php" class="btn btn-secondary">Minhas Vendas</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
