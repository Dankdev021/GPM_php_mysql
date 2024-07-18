<!-- config/auth.php -->

<?php
function redirectIfNotLoggedIn() {
    if (!isset($_SESSION['user'])) {
        header('Location: ../views/login.php');
        exit();
    }
}

function checkAccess($userRole, $allowedRoles) {
    if (!in_array($userRole, $allowedRoles)) {
        header('Location: /oficina/views/unauthorized.php');
        exit();
    }
}
?>
