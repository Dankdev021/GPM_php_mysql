<?php
// interfaces/IUser.php

interface IUser {
    public function create($username, $password, $role);
    public function getByUsername($username);
    public function getById($id);
    public function getAll();
    public function update($id, $username, $password, $role);
    public function delete($id);
}
?>
