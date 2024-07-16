<?php
// interfaces/IUser.php

interface IUser {
    public function register($username, $password, $role);
    public function login($username, $password);
    public function getUserById($id);
}
?>
