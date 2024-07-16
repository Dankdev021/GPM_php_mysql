<!-- views/sales/edit.php -->

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Editar Venda';
$customCSS = ['sales/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/Sale.php';
require_once '../../classes/User.php';
require_once '../../classes/Material.php';

$pdo = db_connect();
$saleModel = new Sale($pdo);
$userModel = new User($pdo);
$materialModel = new Material($pdo);

$sale = $saleModel->getById($_GET['id']);
$users = $userModel->getAll();
$materials = $materialModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Editar Venda</h2>
    <form action="../../controllers/SaleController.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $sale['id']; ?>">
        <div class="form-group">
            <label for="user_id">Usuário</label>
            <select name="user_id" class="form-control" required>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($user['id'] == $sale['user_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($user['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="material_id">Material</label>
            <select name="material_id" class="form-control" required>
                <?php foreach ($materials as $material): ?>
                    <option value="<?php echo $material['id']; ?>" <?php echo ($material['id'] == $sale['material_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($material['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($sale['quantity']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Atualizar</button>
    </form>
</div>

<?php include '../footer.php'; ?>
