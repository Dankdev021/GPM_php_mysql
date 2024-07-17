<?php
// controllers/OrderController.php

require_once '../config/Config.php';
require_once '../classes/Order.php';
require_once '../classes/Material.php';

$action = $_POST['action'];

switch ($action) {
    case 'buy':
        session_start();
        var_dump($_SESSION); // Debug para verificar informações da sessão
        $user_id = $_SESSION['user']['id'];
        $seller_id = $_POST['seller_id']; // Obter o vendedor selecionado
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        $pdo = db_connect();
        $orderModel = new Order($pdo);
        $materialModel = new Material($pdo);

        $material = $materialModel->getById($product_id);

        if ($material && $quantity <= $material['quantity']) {
            if ($orderModel->create($user_id, $seller_id, $product_id, $quantity)) {
                $new_quantity = $material['quantity'] - $quantity;
                $materialModel->updateQuantity($product_id, $new_quantity);
                header('Location: ../views/materials/index.php');
            } else {
                echo "Erro ao processar compra.";
            }
        } else {
            echo "Quantidade solicitada não disponível.";
        }
        break;
}
?>
