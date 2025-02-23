<!-- views/sales/create.php -->

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Adicionar Venda';
$customCSS = ['sales/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/User.php';
require_once '../../classes/Material.php';

$pdo = db_connect();
$userModel = new User($pdo);
$materialModel = new Material($pdo);

$users = $userModel->getAll();
$materials = $materialModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Adicionar Venda</h2>
    <form action="../../controllers/SaleController.php" method="POST">
        <input type="hidden" name="action" value="create">
        <div class="form-group">
            <label for="user_id">Usuário</label>
            <select name="user_id" class="form-control" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>"><?php echo htmlspecialchars($user['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="material_id">Material</label>
            <select name="material_id" class="form-control" required>
                <?php foreach ($materials as $material): ?>
                    <option value="<?php echo $material['id']; ?>"><?php echo htmlspecialchars($material['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Adicionar</button>
    </form>
</div>

<?php include '../footer.php'; ?>
