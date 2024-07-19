<?php
// controllers/MaterialController.php

require_once '../config/Config.php';
require_once '../classes/Material.php';

$action = $_POST['action'];

switch ($action) {
    case 'create':
        // session_start();
        // checkAccess($_SESSION['user']['role'], ['admin']);

        $name = $_POST['name'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $total_price = $quantity * $price;

        $pdo = db_connect();
        $materialModel = new Material($pdo);

        if ($materialModel->create($name, $description, $quantity, $price, $total_price)) {
            header('Location: ../views/materials/index.php');
        } else {
            echo "Erro ao adicionar material.";
        }
        break;

    case 'update':
        // session_start();
        // checkAccess($_SESSION['user']['role'], ['admin']);

        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $total_price = $quantity * $price;

        $pdo = db_connect();
        $materialModel = new Material($pdo);

        if ($materialModel->update($id, $name, $description, $quantity, $price, $total_price)) {
            header('Location: ../views/materials/index.php');
        } else {
            echo "Erro ao adicionar material.";
        }
        break;

    case 'delete':
        // session_start();
        // checkAccess($_SESSION['user']['role'], ['admin']);

        $id = $_POST['id'];

        $pdo = db_connect();
        $materialModel = new Material($pdo);

        if ($materialModel->delete($id)) {
            header('Location: ../views/materials/index.php');
        } else {
            echo "Erro ao deletar material.";
        }
        break;
}
?>
