<!-- views/materials/edit.php -->

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}

$pageTitle = 'Editar Material';
$customCSS = ['materials/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/Material.php';

$pdo = db_connect();
$materialModel = new Material($pdo);
$material = $materialModel->getById($_GET['id']);
?>

<div class="container mt-5">
    <h2 class="text-center">Editar Material</h2>
    <form action="../../controllers/MaterialController.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo $material['id']; ?>">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($material['name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($material['description']); ?>" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($material['quantity']); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($material['price']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Atualizar</button>
    </form>
</div>

<?php include '../footer.php'; ?>
