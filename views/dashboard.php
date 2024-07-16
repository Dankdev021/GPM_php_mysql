<?php
// views/dashboard.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor']);

$pageTitle = 'Dashboard';
$customCSS = [];
include 'header.php';
?>

<div class="container mt-5">
    <h2 class="text-center">Dashboard</h2>
    <p class="text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</p>

    <div class="text-center mt-4">
        <a href="/oficina/views/materials/create.php" class="btn btn-primary">Cadastrar Novo Material</a>
    </div>
</div>

<?php include 'footer.php'; ?>
