<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin']);

$pageTitle = 'Adicionar Usuário';
$customCSS = ['users/style.css'];
include '../header.php';
?>

<div class="container mt-5">
    <h2 class="text-center">Adicionar Usuário</h2>
    <form action="../../controllers/UserController.php" method="POST">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" class="form-control" required>
                <option value="cliente">Cliente</option>
                <option value="vendedor">Vendedor</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success btn-block">Adicionar</button>
    </form>
</div>

<?php include '../footer.php'; ?>
