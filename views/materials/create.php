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
    $total_price = $quantity * $price; // Calcula o preço total

    if ($materialModel->create($name, $description, $quantity, $price, $total_price)) {
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
            <label for="price">Preço Unitário</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <input type="hidden" name="total_price" id="total_price" value="0">
        <button type="submit" class="btn btn-primary btn-block">Adicionar</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var buyButtons = document.querySelectorAll('.btn-primary[data-toggle="modal"]');
        var form = document.querySelector('form');

        form.addEventListener('submit', function(event) {
        var quantity = document.querySelector('input[name="quantity"]').value;
        var price = document.querySelector('input[name="price"]').value;
        var totalPrice = quantity * price;
        document.getElementById('total_price').value = totalPrice;
    });
        
        buyButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var quantity = button.getAttribute('data-quantity');

                var modalProductId = document.getElementById('modalProductId');
                var modalProductName = document.getElementById('modalProductName');
                var modalQuantity = document.getElementById('modalQuantity');
                var quantityHelp = document.getElementById('quantityHelp');

                modalProductId.value = id;
                modalProductName.textContent = name;
                modalQuantity.setAttribute('max', quantity);
                quantityHelp.textContent = 'Quantidade disponível: ' + quantity;
            });
        });
    });
</script>

<?php include '../footer.php'; ?>
