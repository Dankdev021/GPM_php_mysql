<!-- views/unauthorized.php -->

<?php
$pageTitle = 'Acesso Negado';
include 'header.php';
?>

<div class="container mt-5">
    <div class="alert alert-danger text-center" role="alert">
        <h4 class="alert-heading">Acesso Negado!</h4>
        <p>Você não tem permissão para acessar esta página.</p>
        <hr>
        <p class="mb-0">Por favor, <a href="/oficina/views/login.php" class="alert-link">faça login</a> com as credenciais apropriadas.</p>
    </div>
</div>

<?php include 'footer.php'; ?>
