<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciais Erradas</title>
</head>
<body>

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
    <link rel="stylesheet" href="/oficina/assets/css/header/style.css">
    <link rel="icon" href="/oficina/assets/images/favicon.png" type="image/png">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-dark">
        <a class="navbar-brand" href="/oficina/index.php">
            <img src="/oficina/assets/images/favicon.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Oficina
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link btn" href="/oficina/views/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/sales/index.php">Vendas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/services/index.php">Serviços</a>
                        </li>
                    <?php elseif ($_SESSION['user']['role'] === 'vendedor'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/dashboard.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/sales/index.php">Minhas Vendas</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/services/index.php">Meus serviços</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['role'] === 'cliente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/purchases.php">Minhas compras</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/materials/index.php">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/services/create.php">Abrir serviço</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/services/index.php">Meus serviços</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-danger text-light" href="/oficina/controllers/UserController.php?action=logout">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-light" href="/oficina/index.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-light" href="/oficina/index.php">Registrar</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <div class="content" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
        <div class="error-container" style="text-align: center; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
            <h1 style="font-size: 2rem; margin-bottom: 10px;">Credenciais Erradas</h1>
            <p style="font-size: 1.2rem; margin-bottom: 20px;">As credenciais fornecidas estão incorretas. Por favor, tente novamente.</p>
            <a href="/oficina/index.php" style="text-decoration: none; color: #721c24; font-weight: bold; border: 1px solid #721c24; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s;">Voltar</a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="/oficina/assets/js/bootstrap.min.js"></script>
</body>
</html>


<?php include '../views/footer.php'; ?>