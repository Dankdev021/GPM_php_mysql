<!-- views/materials/create.php -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin']);

$pageTitle = 'Adicionar Material';
$customCSS = ['materials/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/Material.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = db_connect();
    $materialModel = new Material($pdo);

    $name = $_POST['name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    if ($materialModel->create($name, $description, $quantity, $price)) {
        header('Location: index.php');
    } else {
        echo "Erro ao adicionar material.";
    }
}
?>

<div class="container mt-5">
    <h2 class="text-center">Adicionar Material</h2>
    <form action="create.php" method="POST">
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <input type="text" name="description" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" name="quantity" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Adicionar</button>
    </form>
</div>

<?php include '../footer.php'; ?>
