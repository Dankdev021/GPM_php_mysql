<?php
function db_connect() {
    $db_host = '127.0.0.1'; // Use '127.0.0.1'(recomendado) ou 'localhost'
    $db_name = 'oficina';
    $db_user = 'root';
    $db_password = '';

    $dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $db_user, $db_password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Erro na conexão: ' . $e->getMessage());
    }
}
?>






