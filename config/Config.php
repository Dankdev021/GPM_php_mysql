<?php
// config/config.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'oficina');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// Conexão com o banco de dados
function db_connect() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}
?>
