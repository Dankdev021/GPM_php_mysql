<!-- views/compras.php -->

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['cliente']);

$pageTitle = 'Compras';
$customCSS = ['compras/style.css'];
include 'header.php';

require_once '../config/Config.php';
require_once '../classes/Product.php';

$pdo = db_connect();
$productModel = new Material($pdo);
$products = $productModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Produtos Disponíveis</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img class="card-img-top" src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($product['description']); ?></p>
                        <p class="card-text">R$ <?php echo htmlspecialchars($product['price']); ?></p>
                        <form action="../controllers/OrderController.php" method="POST">
                            <input type="hidden" name="action" value="buy">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <button type="submit" class="btn btn-primary">Comprar</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$customJS = ['compras/script.js'];
include 'footer.php';
?>
