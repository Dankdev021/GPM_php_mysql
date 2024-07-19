<?php
// controllers/UserController.php

require_once '../config/Config.php';
require_once '../classes/User.php';
require_once '../classes/ServiceOrder.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

switch ($action) {
    case 'login':
        session_start();
        $username = $_POST['username'];
        $password = $_POST['password'];

        $pdo = db_connect();
        $userModel = new User($pdo);
        $user = $userModel->login($username, $password);

        if ($user) {
            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ];

            // Redirecionar para a página correta com base no papel do usuário
            if ($user['role'] === 'admin' || $user['role'] === 'vendedor') {
                header('Location: ../views/dashboard.php');
            } elseif ($user['role'] === 'cliente') {
                header('Location: ../views/materials/index.php');
            }
        } else {
            echo "Login falhou. Verifique suas credenciais.";
        }
        break;

    case 'register':
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        $pdo = db_connect();
        $userModel = new User($pdo);
        if ($userModel->register($username, $password, $role)) {
            header('Location: ../views/login.php');
        } else {
            echo "Erro ao registrar usuário.";
        }
        break;

    case 'create':
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        $pdo = db_connect();
        $userModel = new User($pdo);
        if ($userModel->register($username, $password, $role)) {
            header('Location: ../views/users/index.php');
        } else {
            echo "Erro ao registrar usuário.";
        }
        break;

    case 'logout':
        session_start();
        session_destroy();
        header('Location: ../views/login.php');
        break;

    case 'delete':
        $id = $_POST['id'];

        $pdo = db_connect();
        $userModel = new User($pdo);
        if ($userModel->delete($id)) {
            header('Location: ../views/users/index.php');
        } else {
            echo "Erro ao deletar usuário.";
        }
        break;

    case 'hire_service':
        session_start();
        $service_id = $_POST['service_id'];
        $user_id = $_SESSION['user']['id'];
        $seller_id = $_POST['seller_id'];

        $pdo = db_connect();
        $serviceOrderModel = new ServiceOrder($pdo);
        if ($serviceOrderModel->createOrder($service_id, $user_id, $seller_id)) {
            header('Location: ../views/services/index.php');
        } else {
            echo "Erro ao contratar serviço.";
        }
        break;

    default:
        echo "Ação não reconhecida.";
        break;
}
?>
