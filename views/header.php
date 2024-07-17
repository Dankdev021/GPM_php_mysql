<!-- views/header.php -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle : 'Oficina'; ?></title>
    <link rel="stylesheet" href="/oficina/assets/css/bootstrap.min.css">
    <?php
    if (isset($customCSS)) {
        foreach ($customCSS as $css) {
            echo "<link rel='stylesheet' href='/oficina/assets/css/$css'>";
        }
    }
    ?>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Oficina</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'vendedor'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/dashboard.php">Dashboard</a>
                        </li>
                        <?php if ($_SESSION['user']['role'] === 'vendedor'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/oficina/views/sales/index.php">Minhas Vendas</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['role'] === 'cliente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/purchases.php">Compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/materials/index.php">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/services/index.php">Serviços</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/controllers/UserController.php?action=logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/views/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/views/register.php">Registrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
