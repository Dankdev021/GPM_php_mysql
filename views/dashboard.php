<?php
session_start();
require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor']);

$pageTitle = 'Dashboard';
include 'header.php';

$role = $_SESSION['user']['role'];
?>

<div class="container mt-5">
    <h2 class="text-center">Dashboard</h2>
    <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>

    <?php if ($role === 'admin'): ?>
        <div class="text-center mt-4">
            <a href="materials/create.php" class="btn btn-primary">Cadastrar Novo Material</a>
        </div>
    <?php endif; ?>

    <?php if ($role === 'vendedor'): ?>
        <div class="text-center mt-4">
            <a href="sales/index.php" class="btn btn-secondary">Minhas Vendas</a>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
