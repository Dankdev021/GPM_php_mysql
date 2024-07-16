<?php
// controllers/UserController.php

require_once '../config/Config.php';
require_once '../classes/User.php';

$action = $_POST['action'];

switch ($action) {
    case 'register':
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role']; // Adicionando o campo role

        $pdo = db_connect();
        $userModel = new User($pdo);

        if ($userModel->create($username, $password, $role)) {
            header('Location: ../views/login.php');
        } else {
            echo "Erro ao registrar usuário.";
        }
        break;

    case 'login':
        $username = $_POST['username'];
        $password = $_POST['password'];

        $pdo = db_connect();
        $userModel = new User($pdo);
        $user = $userModel->getByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user'] = $user;
            // Redirecionamento baseado no tipo de usuário
            if ($user['role'] === 'admin' || $user['role'] === 'vendedor') {
                header('Location: ../views/dashboard.php');
            } else if ($user['role'] === 'cliente') {
                header('Location: ../views/materials/index.php');
            }
        } else {
            echo "Nome de usuário ou senha incorretos.";
        }
        break;

    case 'logout':
        session_start();
        session_destroy();
        header('Location: ../views/login.php');
        break;
}
?>
