<?php
// controllers/SelectSellerController.php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['seller_id'])) {
        $_SESSION['selected_seller'] = $_POST['seller_id'];
        header('Location: ../views/materials/index.php');
        exit();
    }
}

header('Location: ../views/materials/index.php');
?>
