<?php
// classes/User.php

require_once __DIR__ . '/../interfaces/IUser.php';

class User implements IUser {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function create($username, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $password, $role]);
    }

    public function getByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $username, $password, $role) {
        $stmt = $this->db->prepare("UPDATE users SET username = ?, password = ?, role = ? WHERE id = ?");
        return $stmt->execute([$username, $password, $role, $id]);
    }

    public function delete($id) {
        $this->deleteServiceOrder($id);
        $this->deleteOrder($id);
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteServiceOrder($id) {
        $delete = $this->db->prepare("DELETE FROM service_orders WHERE mechanic_id = ?");
        return $delete->execute([$id]);
    }

    public function deleteOrder($id) {
        $delete = $this->db->prepare("DELETE FROM orders WHERE user_id = ?");
        return $delete->execute([$id]);
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function register($username, $password, $role) {
        $stmt = $this->db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $password, $role]);
    }

    public function getUsersByRole($role) {
        $stmt = $this->db->prepare("SELECT id, username FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
