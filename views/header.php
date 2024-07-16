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
                    <?php endif; ?>
                    <?php if ($_SESSION['user']['role'] === 'cliente'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/oficina/views/materials/index.php">Compras</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/public/logout.php">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/views/login.php">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/oficina/views/register.php">Register</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <?php if (isset($_SESSION['cliente']) && $_SESSION['cliente']['role'] === 'cliente' && !isset($_SESSION['selected_seller'])): ?>
        <div class="modal fade" id="selectSellerModal" tabindex="-1" role="dialog" aria-labelledby="selectSellerModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="selectSellerModalLabel">Selecionar Vendedor</h5>
                    </div>
                    <form action="/oficina/controllers/SelectSellerController.php" method="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="seller">Selecione um Vendedor:</label>
                                <select name="seller_id" id="seller" class="form-control" required>
                                    <option value="" selected disabled>Selecione um Vendedor</option>
                                    <?php
                                    require_once '../config/Config.php';
                                    $pdo = db_connect();
                                    $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'vendedor'");
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Confirmar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $('#selectSellerModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#selectSellerModal').modal('show');
            });
        </script>
    <?php endif; ?>
