<?php
// interfaces/ISale.php

interface ISale {
    public function create($user_id, $material_id, $quantity);
    public function getAll();
    public function getById($id);
    public function update($id, $user_id, $material_id, $quantity);
    public function delete($id);
}
?>
